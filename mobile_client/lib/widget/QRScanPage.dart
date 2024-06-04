import 'package:flutter/material.dart';
import 'package:qr_code_scanner/qr_code_scanner.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:flutter_session_manager/flutter_session_manager.dart';
import 'package:helpdesk/model/Segnalazione.dart';
import 'package:helpdesk/model/Categoria.dart';


class QRScanPage extends StatefulWidget {
  @override
  _QRScanPageState createState() => _QRScanPageState();
}

class _QRScanPageState extends State<QRScanPage> {
  final qrKey = GlobalKey(debugLabel: 'QR');
  QRViewController? controller;
  String? qrData;
  String tipo = '';
  @override
  Widget build(BuildContext context) {
    final txtController = TextEditingController();
            final TextEditingController catController = TextEditingController();
    return Scaffold(
      body: qrData == null
          ? QRView(
              key: qrKey,
              onQRViewCreated: _onQRViewCreated,
            )
          : FutureBuilder<String>(
  future: qrData!.contains('dispositivo') ? getDevice(qrData!) : getAula(qrData!),
  builder: (BuildContext context, AsyncSnapshot<String> snapshot) {
    if (snapshot.connectionState == ConnectionState.waiting) {
      return CircularProgressIndicator();
    } else if (snapshot.hasError) {
      return Text('Error: ${snapshot.error}');
    } else {
      Map<String, dynamic> data = jsonDecode(snapshot.data!);
      List<Widget> widgets = [];
      data.forEach((key, value) {
        if (key != 'id' && key != 'mostra_sulla_mappa' && key != 'id_piano') {
          widgets.add(
            Center(
              child: Text(
                '$value',
                style: TextStyle(fontSize: 24), // Larger font size
              ),
            ),
          );
        }
      });
      widgets.add(
        Center(
          child: ElevatedButton(
            onPressed:() async{
            var txt = '';
            var id_cat = null;
            
            final url = Uri.parse('http://100.101.216.250/helpdesk_API/categoria');
            final sid = await getSession();
            final response = await http.get(url,
              headers: <String, String>{
                'x-ssid' : sid,
          });
          if (response.statusCode == 200) {
            final data = jsonDecode(response.body) as List<dynamic>;
            List<Categoria> catList = data.map((item) => Categoria.fromJson(item)).toList();
            
            showDialog(
              context: context,
              builder: (BuildContext context) {
                return AlertDialog(
      title: Text('Segnalazione '),
      content:  SingleChildScrollView(
        child: ListBody(
          children: <Widget>[
            Text('Seleziona la categoria della segnalazione'),
            DropdownMenu<Categoria>(
              enableFilter: true,
                controller: catController,
              onSelected: (cat) => id_cat = cat,
              dropdownMenuEntries: catList.map<DropdownMenuEntry<Categoria>>(
                (Categoria cat) {
                  return DropdownMenuEntry<Categoria>(
                    value: cat,
                    label: cat.nome,
                  );
                },
              ).toList(),
            ),
            Text('Descrivi il problema'),
            TextField(
              controller: txtController,
              onChanged: (text) {
                txt = text;
              },
            ),
            ElevatedButton(onPressed: () async { await segnalazione(data[0]['id'],id_cat,txtController,tipo); 
            Navigator.of(context).pop();}, 
            child: const Text('Segnala')),
          ],
        ),
        )
        
      );
              }
            ,
            );
          } else {
            // Handle error (e.g., print error message)
            print('Failed to fetch data: ${response.body}');
          }


          }, // Call segnalazione when pressed
            child: Text('Segnalazione'),
          ),
        ),
      );
      return Column(children: widgets);
    }
  },
),
    );
  }

  void _onQRViewCreated(QRViewController controller) {
    this.controller = controller;
    controller.scannedDataStream.listen((scanData) {
      controller.pauseCamera();
      setState(() {
        qrData = scanData.code;
      });
    });
  }

  dynamic getSession(){
    var sessionManager = SessionManager();
    return getFutureValue(sessionManager.get("id"));
  }

  Future<String> getFutureValue(Future<dynamic> futureValue) async {
    var value = await futureValue;
    return value.toString();
  }

  Future<void> segnalazione(id,cat,txt,type) async{
    String txts = txt.text;
    var id_aula = null;
    var id_dispositivo = null;
    if(type=='dispositivo'){
      id_dispositivo = id;
    } else {
      id_aula = id;
    }
    final segn = new Segnalazione(id_aula: id_aula, id_categoria: cat.id, id_dispositivo: id_dispositivo, testo_segnalazione: txts);
    final url = Uri.parse('http://100.101.216.250/helpdesk_API/segnalazione');
    final sid = await getSession();
    print(sid);
    
    
  await http.post(
    url,
    headers: <String, String>{
      'Content-Type': 'application/json; charset=UTF-8',
      'x-ssid' : sid,
    },
    body: jsonEncode({
      'id_aula': segn.id_aula,
      'id_categoria': segn.id_categoria,
      'id_dispositivo': segn.id_dispositivo,
      'testo_segnalazione': segn.testo_segnalazione,
    }));
  }
  

  Future<String> getDevice(String url) async {
    tipo = 'dispositivo';
    Uri uri = Uri.parse(url);
    try {
      final response = await http.get(uri, headers: <String, String>{
        'x-ssid' : await getSession(),
      });

      if (response.statusCode == 200) {
        return response.body;
      } else {
        throw Exception('Server returned an error: ${response.statusCode}');
      }
    } catch (e) {
      throw Exception('Error occurred while making the GET request: $e');
    }
  }

  Future<String> getAula(String url) async {
    tipo = 'aula';
    Uri uri = Uri.parse(url);
    try {
      final response = await http.get(uri, headers: <String, String>{
        'x-ssid' : await getSession(),
      });

      if (response.statusCode == 200) {
        return response.body;
      } else {
        throw Exception('Server returned an error: ${response.statusCode}');
      }
    } catch (e) {
      throw Exception('Error occurred while making the GET request: $e');
    }
  }

  

  @override
  void dispose() {
    controller?.dispose();
    super.dispose();
  }
}

