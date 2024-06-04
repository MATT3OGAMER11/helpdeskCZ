import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import '../localdb/dao/dao.dart';
import '../localdb/AppDatabase.dart';
import '../localdb/table/model.dart';
import '../model/Segnalazione.dart';

// Assuming Aula.dart is in the same directory
import '../model/AulaM.dart';
import '../model/Categoria.dart';
import '../localdb/table/model.dart';
import '../widget/Dispositivipage.dart';
import 'package:flutter_session_manager/flutter_session_manager.dart';

class AulaPage extends StatelessWidget {
  final Future<List<Aula>> aule;
  const AulaPage({Key? key, required this.aule, }) : super(key: key);

  

  @override
  Widget build(BuildContext context) {
    return FutureBuilder<List<Aula>>(
      future: aule, // Use the provided future
      builder: (context, snapshot) {
        if (snapshot.hasData) {
          final aule = snapshot.data!;
          // ... your code to display Aula data in a scrollable list (using aule)
          return YourAulaListWidget(
              aule: aule); // Example: Pass data to a dedicated widget
        } else if (snapshot.hasError) {
          return Center(child: Text('${snapshot.error}'));
        }
        return Center(child: CircularProgressIndicator());
      },
    );
  }
}

class YourAulaListWidget extends StatelessWidget {
  
  final List<Aula> aule;
  const YourAulaListWidget({Key? key, required this.aule}) : super(key: key);
  
  Future<Future<List<Dispositivo?>>> selectAula (Aulaid) async {
    final appDatabase =
            await $FloorAppDatabase.databaseBuilder('app_database.db').build();
        final DispositivoDao = appDatabase.dispositivoDao;
        return DispositivoDao.getDispositiviByAula(Aulaid);

        
  }

  dynamic getSession(){
    var sessionManager = SessionManager();
    return getFutureValue(sessionManager.get("id"));
  }

  Future<String> getFutureValue(Future<dynamic> futureValue) async {
  var value = await futureValue;
  return value.toString();
}

  Future<void> segnalazione(id,cat,txt) async{
    String txts = txt.text;
    final segn = new Segnalazione(id_aula: id, id_categoria: cat.id, id_dispositivo: null, testo_segnalazione: txts);
    final url = Uri.parse('http://100.101.216.250/helpdesk_API/segnalazione');
    final sid = await getSession();
    print(sid);
  final response = await http.post(
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
    
    }),
  );

  if (response.statusCode == 200) {
    // If the server returns a 200 OK response,
    // then parse the JSON.
    Map<String, dynamic> jsonResponse = jsonDecode(response.body);
    print(jsonResponse);
  } else {
    // If the server did not return a 200 OK response,
    // then throw an exception.
    print (response.statusCode);
    print (response.body);
    throw Exception('Errore');
  }
  }

  @override
  Widget build(BuildContext context) {
    final txtController = TextEditingController();
            final TextEditingController catController = TextEditingController();
    return ListView.builder(
      itemCount: aule.length,
      itemBuilder: (context, index) {
        final aula = aule[index];
        // ... your code to display Aula data for each item
        return ListTile(
          title: Text(aula.name),
          leading: Text(aula.type),
          onTap: () async {
            Future<List<Dispositivo?>> dispositivi = await selectAula(aula.id);
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (context) => DispositiviPage(dispositivi: dispositivi),
            ),
          );
          },
          onLongPress:() async{
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
      title: Text('Segnalazione '+aula.name),
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
            ElevatedButton(onPressed: () async { await segnalazione(aula.id,id_cat,txtController); 
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


          },
            // Call navigation function
          // Example: Display Aula name
        );
      },
    );
  }
}
