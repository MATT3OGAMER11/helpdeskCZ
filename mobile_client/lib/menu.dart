import 'package:flutter/material.dart';

class MenuItem {
  final IconData icon;
  final String title;

  const MenuItem({required this.icon, required this.title});
}

class Menu extends StatelessWidget {
  final List<MenuItem> items;
  final ValueChanged<int> onItemSelected;

  const Menu({required this.items, required this.onItemSelected});

  @override
  Widget build(BuildContext context) {
    return Drawer(
      child: ListView.builder(
        itemCount: items.length,
        itemBuilder: (context, index) {
          final item = items[index];
          return ListTile(
            leading: Icon(item.icon),
            title: Text(item.title),
            onTap: () => onItemSelected(index),
          );
        },
      ),
    );
  }
}
