import 'package:flutter/material.dart';
import 'package:helpdesk/widget/UserInfo.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'localdb/dao/dao.dart';
import 'localdb/AppDatabase.dart';
import 'localdb/table/model.dart';
import 'package:random_string/random_string.dart';
import 'package:flutter_session_manager/flutter_session_manager.dart';

// Assuming Aula.dart is in the same directory
import 'model/AulaM.dart';
import 'widget/Aulapage.dart';
import 'widget/HomePage.dart';
import 'widget/MenuDrawer.dart';
import 'widget/LoginPage.dart';
import 'widget/QRScanPage.dart';

class MyApp extends StatefulWidget {
  @override
  _MyAppState createState() => _MyAppState();
}

class _MyAppState extends State<MyApp> {
  int _selectedIndex = 0;
  Future<List<Aula>>? _aule;
  var sessionManager = SessionManager();

  @override
  void initState() {
    super.initState();
    print("initState");
    //fetchAndInsertPianos();
    _aule = obtainAule();
    print("fetched aule");
    var sessionID = 'FLUTTER';
    sessionID += randomString(30);

    initSession(sessionManager, sessionID);
  }

  

  Future<void> initSession(
      SessionManager sessionManager, String sessionID) async {
    //await sessionManager.set("id", sessionID);
    printFutureValue(sessionManager.get("id"));
    //print(getFutureValue(sessionManager.get("id")));
  }

  Future<void> fetchAndInsertPianos() async {
     Uri url = Uri.parse('http://100.101.216.250/helpdesk_API/piani');
    try {
      var sid = await getSession();
      final response = await http.get(url, headers: <String, String>{
      'x-ssid' : sid,
    },);

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body) as List<dynamic>;

        // Get access to the PianoDao
        final appDatabase =
            await $FloorAppDatabase.databaseBuilder('app_database.db').build();
        final pianoDao = appDatabase.pianoDao;
        await pianoDao.deleteAll();

        // Insert each element in the JSON list into the piano table
        for (final element in data) {
          await pianoDao
              .insertPiano(Piano(id: element['id'], name: element['nome']));
        }
        print('Pianos inserted successfully!');
      } else {
        // Handle error (e.g., print error message)
        
        print('Failed to fetch data: ${response.statusCode}');
      }
    } catch (error) {
      // Handle exceptions (e.g., network issues)
      print('Error fetching data: $error');
    }
    url = Uri.parse('http://100.101.216.250/helpdesk_API/aule');
    try {
      var sid = await getSession();
      print(sid);
      final response = await http.get(url, headers: <String, String>{
      'x-ssid' : sid,
    });

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body) as List<dynamic>;

        // Get access to the PianoDao
        final appDatabase =
            await $FloorAppDatabase.databaseBuilder('app_database.db').build();
        final AulaDao = appDatabase.aulaDao;

        // Insert each element in the JSON list into the piano table
        for (final element in data) {
          if (element['mostra_sulla_mappa'] == 1) {
            element['mostra_sulla_mappa'] = true;
          } else {
            element['mostra_sulla_mappa'] = false;
          }
          await AulaDao.insertAula(Aula(
              id: element['id'],
              numero: element['numero'],
              name: element['nome'],
              pianoId: element['id_piano'],
              type: element['tipo'],
              showsOnMap: element['mostra_sulla_mappa']));
        }
        print('aule inserted successfully!');
      } else {
        // Handle error (e.g., print error message)
        print(response.body);
        print('Failed to fetch data: ${response.statusCode}');
      }
    } catch (error) {
      // Handle exceptions (e.g., network issues)
      print('Error fetching data: $error');
    }
    url = Uri.parse('http://100.101.216.250/helpdesk_API/dispositivi');
    try {
      var sid = await getSession();
      
      final response = await http.get(url, headers: <String, String>{
      'x-ssid' : sid,
    });

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body) as List<dynamic>;

        // Get access to the PianoDao
        final appDatabase =
            await $FloorAppDatabase.databaseBuilder('app_database.db').build();
        final DispositivoDao = appDatabase.dispositivoDao;

        // Insert each element in the JSON list into the piano table
        for (final element in data) {
          await DispositivoDao.insertDispositivo(Dispositivo(
              id: element['id'],
              name: element['nome'],
              type: element['tipo'],
              aulaId: element['id_aula']));
        }
        print('aule inserted successfully!');
        final obj = DispositivoDao.getAllDispositivi();
        // ignore: unused_local_variable
        print(obj);
      } else {
        // Handle error (e.g., print error message)
        print('Failed to fetch data: ${response.statusCode}');
      }
    } catch (error) {
      // Handle exceptions (e.g., network issues)
      print('Error fetching data: $error');
    }
  }

  Future<void> printFutureValue(Future<dynamic> futureValue) async {
  var value = await futureValue;
  print(value);
}

Future<String> getFutureValue(Future<dynamic> futureValue) async {
  var value = await futureValue;
  return value.toString();
}

  Future<String> getSession() async {
    var sessionManager = SessionManager();
    return getFutureValue(sessionManager.get("id"));
  }

  Future<List<Aula>> fetchAule() async {
    //final response =
    //await http.get(Uri.parse('http://192.168.1.86/helpdesk_API/aule')); //DESKTOP
    //await http.get(Uri.parse('http://192.168.50.97/helpdesk_API/aule')); //LAPTOP
    //await http.get(Uri.parse('http://100.100.111.10/helpdesk_API/aule')); //VPN LAPTOP
    //await http.get(Uri.parse('http:///helpdesk_API/aule')); //VPN DESKTOP
    // Get access to the PianoDao
    final appDatabase =
        await $FloorAppDatabase.databaseBuilder('app_database.db').build();
    final AulaDao = appDatabase.aulaDao;
    final aule = await AulaDao.getAllAule();
    return aule;

    /*if (response.statusCode == 200) {
      final data = jsonDecode(response.body) as List;
      print(data);
      fetchAndInsertPianos();
      return data.map((item) => AulaM.fromJson(item)).toList();
    } else {
      throw Exception('Failed to load aule data');
    }*/
  }
  Future<List<Aula>> obtainAule() async {
    final appDatabase =
            await $FloorAppDatabase.databaseBuilder('app_database.db').build();
        final aulaDao = appDatabase.aulaDao;
        return aulaDao.getAllAule();
  }

  void _onAulaSelected() async {
    _aule = obtainAule();
    setState(() {
      _selectedIndex = 1; // Update selected index for Aula page
    });
  }

  void _onHomeSelected() {
    setState(() {
      _selectedIndex = 0;
    });
  }

  void _onQrSelected() {
    setState(() {
      _selectedIndex = 2;
    });
  }

  void _onLoginSelected() {
    setState(() {
      _selectedIndex = 3;
    });
  }

  void _onInfoSelected(){
    setState((){
      _selectedIndex = 4;
    });
  }



  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      home: Scaffold(
        appBar: AppBar(
          title: Text('HELPDESK CZ'),
        ),
        body: IndexedStack(
          // Use IndexedStack to switch between pages
          index: _selectedIndex,
          children: [
            HomePage(), // Your HomePage widget
            AulaPage(aule: _aule!), // Aula page with fetched data
            QRScanPage(),
            LoginPage(),
            UserInfo(),
          ],
        ),
        drawer: MenuDrawer(
          menuItems: [
            MenuItem(
                title: 'Home', onTap: () => _onHomeSelected()), // Close drawer
            MenuItem(
                title: 'Aule',
                onTap: _onAulaSelected), // Existing Aula selection
            MenuItem(
                title: 'QR',
                onTap: _onQrSelected), // Example route to Settings page
            MenuItem(title: 'LOGIN', onTap: _onLoginSelected),
            MenuItem(title: 'UPDATE FILES', onTap: fetchAndInsertPianos),
            MenuItem(title: 'ACCOUNT', onTap: _onInfoSelected)
            // Add more menu items here
          ],
          onAulaSelected: _onAulaSelected,
        ), // Add menu drawer
      ),
    );
  }
}

//named routes for pages
const String homeRoute = '/';
const String auleRoute = '/aule';
const String qrRoute = '/qr';
const String dispositiviRoute = '/dispositivi/@id_aula';

void main() {
  runApp(MyApp());
}
