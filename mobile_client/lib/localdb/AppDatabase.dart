import 'dart:async';

import 'package:floor/floor.dart';
import 'package:sqflite/sqflite.dart' as sqflite;

import 'dao/Dao.dart';
import 'table/model.dart';

part 'AppDatabase.g.dart'; // the generated code will be there

@Database(version: 1, entities: [Piano, Aula, Dispositivo])
abstract class AppDatabase extends FloorDatabase {
  PianoDao get pianoDao;
  AulaDao get aulaDao;
  DispositivoDao get dispositivoDao;
}
