import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:random_string/random_string.dart';
import 'package:flutter_session_manager/flutter_session_manager.dart';
import '../model/Utente.dart';

class LoginPage extends StatefulWidget {
  @override
  
  _LoginPageState createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();

  @override
  void dispose() {
    _emailController.dispose();
    super.dispose();
  }

  void newSession() async {
    var sessionID = 'FLUTTER';
    sessionID += randomString(30);
    var sessionManager = SessionManager();
    initSession(sessionManager, sessionID);
  }

  void insertUserInSession(Utente utente) async {
    var sessionManager = SessionManager();
    await sessionManager.set("utente", utente);
    print('userInsession');
  }

  Future<void> initSession(
      SessionManager sessionManager, String sessionID) async {
    await sessionManager.set("id", sessionID);
  }

  dynamic getSession(){
    var sessionManager = SessionManager();
    return getFutureValue(sessionManager.get("id"));
  }

  Future<String> getFutureValue(Future<dynamic> futureValue) async {
  var value = await futureValue;
  return value.toString();
}

  @override
  Widget build(BuildContext context) {
    
    return Scaffold(
      appBar: AppBar(
        title: Text('Login'),
      ),
      body: Form(
        key: _formKey,
        child: Column(
          children: <Widget>[
            TextFormField(
              controller: _emailController,
              decoration: InputDecoration(labelText: 'Email'),
              validator: (value) {
                if (value == null || value.isEmpty) {
                  return 'Inserisci la tua email';
                }
                return null;
              },
            ),
            ElevatedButton(
  onPressed: () async {
    if (_formKey.currentState!.validate()) {
      // Process data.
      newSession();
      final sid = await getSession();
      var email = _emailController.text;
      var url = Uri.parse('http://100.101.216.250/helpdesk_API/utente/$email');
      var response = await http.get(
        url,
        headers: <String, String>{
          'x-ssid' : sid,
        },
      );
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
        throw Exception('Failed to load data');
      }
    }
  },
  child: Text('Submit'),
),
          ],
        ),
      ),
    );
  }
}