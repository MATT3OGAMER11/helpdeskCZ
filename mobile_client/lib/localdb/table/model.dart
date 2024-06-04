import 'package:floor/floor.dart';

@Entity(tableName: 'piano')
class Piano {
  @PrimaryKey(autoGenerate: true)
  final int id;

  @ColumnInfo(name: 'nome')
  final String name;

  Piano({
    required this.id,
    required this.name,
  });
}

@Entity(tableName: 'aula', foreignKeys: [
  ForeignKey(
      childColumns: ['id_piano'],
      parentColumns: ['id'],
      entity: Piano,
      onDelete: ForeignKeyAction.cascade)
])
class Aula {
  @PrimaryKey()
  final int id;

  @ColumnInfo(name: 'numero')
  final int? numero;

  @ColumnInfo(name: 'nome')
  final String name;

  @ColumnInfo(name: 'tipo')
  final String type;

  @ColumnInfo(name: 'mostra_sulla_mappa')
  final bool showsOnMap;

  @ColumnInfo(name: 'id_piano')
  final int pianoId;

  Aula({
    required this.id,
    required this.numero,
    required this.name,
    required this.type,
    required this.showsOnMap,
    required this.pianoId,
  });
}

@Entity(tableName: 'dispositivo', foreignKeys: [
  ForeignKey(
      childColumns: ['id_aula'],
      parentColumns: ['id'],
      entity: Aula,
      onDelete: ForeignKeyAction.cascade)
])
class Dispositivo {
  @PrimaryKey()
  final int id;

  @ColumnInfo(name: 'nome')
  final String name;

  @ColumnInfo(name: 'tipo')
  final String type;

  @ColumnInfo(name: 'id_aula')
  final int aulaId;

  Dispositivo({
    required this.id,
    required this.name,
    required this.type,
    required this.aulaId,
  });
}
