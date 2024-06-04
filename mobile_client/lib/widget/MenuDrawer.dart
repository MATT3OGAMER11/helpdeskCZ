import 'package:flutter/material.dart';

class MenuDrawer extends StatelessWidget {
  final List<MenuItem> menuItems;
  final Function onAulaSelected;

  const MenuDrawer(
      {Key? key, required this.menuItems, required this.onAulaSelected})
      : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Drawer(
      child: ListView.builder(
        itemCount: menuItems.length,
        itemBuilder: (context, index) {
          final menuItem = menuItems[index];
          return ListTile(
            title: Text(menuItem.title),
            onTap: () {
              if (menuItem.onTap != null) {
                menuItem.onTap!(); // Call the provided function
              } else if (menuItem.routeName != null) {
                Navigator.pushNamed(
                    context, menuItem.routeName!); // Navigate to route
              }
              Navigator.pop(context); // Close drawer
            },
          );
        },
      ),
    );
  }
}

class MenuItem {
  final String title;
  final Function? onTap; // Optional callback for custom actions
  final String? routeName; // Optional route name for navigation

  const MenuItem({
    required this.title,
    this.onTap,
    this.routeName,
  });
}
