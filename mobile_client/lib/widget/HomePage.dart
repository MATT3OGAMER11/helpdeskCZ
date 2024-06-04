import 'package:flutter/material.dart';

class HomePage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text(
            'Benvenuto su helpdesk',
            style: TextStyle(fontSize: 24),
          ),
          SizedBox(height: 20),
          Text(
            'home',
          ),
        ],
      ),
    );
  }
}
