import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:flutter_session_manager/flutter_session_manager.dart';
import '../model/Utente.dart';
import 'HomePage.dart';

class UserInfo extends StatelessWidget {
  
  void logout(BuildContext context) {
  print('Logout');
  final session = SessionManager();
  session.set('utente', null);
  out(context);
}

 void out(context) async {
  final response = await http.delete(
    Uri.parse('http://100.101.216.250/helpdesk_API/utente/logout'),
    headers: <String, String>{
      'x-ssid': await getSession(),
    },
  );

  if (response.statusCode == 200) {
    print('User successfully logged out.');
    Navigator.of(context).pushReplacement(
      MaterialPageRoute(builder: (context) => HomePage()),
    );
  } else {
    throw Exception('Failed to log out user.');
  }
}

Future<void> updateUser(Utente utente) async {
  final response = await http.put(
    Uri.parse('http://100.101.216.250/helpdesk_API/utenti'), // Replace with your API URL
    headers: <String, String>{
      'Content-Type': 'application/json; charset=UTF-8',
      'x-ssid': await getSession(), // Assuming you use token for authorization
    },
    body: jsonEncode(utente.toJson()),
  );

  if (response.statusCode == 200) {
    print('User successfully updated.');
    final sid = await getSession();
      //var email = _emailController.text;
      int id = utente.id;
      var url = Uri.parse('http://100.101.216.250/helpdesk_API/utente/$id');
      var response = await http.get(
        url,
        headers: <String, String>{
          'x-ssid' : sid,
  });
  if (response.statusCode == 200) {
        // If the server returns a 200 OK response,
        // then parse the JSON.
        Map<String, dynamic> jsonResponse = jsonDecode(response.body);
        Utente utente = Utente.fromJson(jsonResponse);
        print('Utente id: ${utente.id}');
        insertUserInSession(utente);
      } else {
        // If the server did not return a 200 OK response,
        // then throw an exception.
        print('Response body: ${response.body}');
        print(utente.id);
        throw Exception('Failed to load data');
      }
  } else {
    print(response.body);
    throw Exception('Failed to update user.');
  }
}

void insertUserInSession(Utente utente) async {
    var sessionManager = SessionManager();
    await sessionManager.set("utente", utente);
    print('userInsession');
  }



Future<String> getFutureValue(Future<dynamic> futureValue) async {
  var value = await futureValue;
  return value.toString();
}

  Future<String> getSession() async {
    var sessionManager = SessionManager();
    return getFutureValue(sessionManager.get("id"));
  }
  Future<Utente> getUtenteFromSession() async {
  final session = SessionManager();
  final userData = await session.get('utente');
  print(userData.toString());
  return Utente(
    id: userData['id'],
    nome: userData['nome'],
    cognome: userData['cognome'],
    email: userData['email'],
    idRuolo: userData['id_ruolo'],
    dataCreazione: DateTime.parse(userData['data_creazione']), 
    token: userData['token'],
  );
}

  @override
  Widget build(BuildContext context) {
    return FutureBuilder<Utente>(
      future: getUtenteFromSession(),
      builder: (BuildContext context, AsyncSnapshot<Utente> snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return CircularProgressIndicator();
        } else if (snapshot.hasError) {
          return Text('Error: ${snapshot.error}');
        } else {
          Utente usr = snapshot.data!;
          String ruoloName = 'base';
          if(usr.idRuolo == 2){
            ruoloName = 'tecninco';
          } else if(usr.idRuolo == 3){
            ruoloName = 'personaleATA';
          } else if(usr.idRuolo == 4){
            ruoloName = 'admin';
          }

          // Initialize the TextEditingController with the user's name and surname
          final controllerName = TextEditingController(text: usr.nome);
          final controllerCognome = TextEditingController(text: usr.cognome);

          return Column(
            children: [
              TextField(
                controller: controllerName,
                decoration: InputDecoration(
                  labelText: 'Nome',
                ),
              ),
              TextField(
                controller: controllerCognome,
                decoration: InputDecoration(
                  labelText: 'Cognome',
                ),
              ),
              Text('Email: ${usr.email}'),
        Text('Ruolo: ${ruoloName}'),
        Text('Data Creazione: ${usr.dataCreazione}'),
        ElevatedButton(
          onPressed: ( ) {
            logout(context);
            // TODO: Implement logout functionality
          },
          child: Text('Logout'),
        ),
              // Rest of your code
              ElevatedButton(
                onPressed: () {
                  usr.nome = controllerName.text;
                  usr.cognome = controllerCognome.text; // Corrected this line
                  print(usr.nome);
                  updateUser(usr);
                  // TODO: Implement modify functionality
                },
                child: Text('Modifica'),
              ),
            ],
          );
        }
      },
    );
  }
}