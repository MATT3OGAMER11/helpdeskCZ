// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'AppDatabase.dart';

// **************************************************************************
// FloorGenerator
// **************************************************************************

abstract class $AppDatabaseBuilderContract {
  /// Adds migrations to the builder.
  $AppDatabaseBuilderContract addMigrations(List<Migration> migrations);

  /// Adds a database [Callback] to the builder.
  $AppDatabaseBuilderContract addCallback(Callback callback);

  /// Creates the database and initializes it.
  Future<AppDatabase> build();
}

// ignore: avoid_classes_with_only_static_members
class $FloorAppDatabase {
  /// Creates a database builder for a persistent database.
  /// Once a database is built, you should keep a reference to it and re-use it.
  static $AppDatabaseBuilderContract databaseBuilder(String name) =>
      _$AppDatabaseBuilder(name);

  /// Creates a database builder for an in memory database.
  /// Information stored in an in memory database disappears when the process is killed.
  /// Once a database is built, you should keep a reference to it and re-use it.
  static $AppDatabaseBuilderContract inMemoryDatabaseBuilder() =>
      _$AppDatabaseBuilder(null);
}

class _$AppDatabaseBuilder implements $AppDatabaseBuilderContract {
  _$AppDatabaseBuilder(this.name);

  final String? name;

  final List<Migration> _migrations = [];

  Callback? _callback;

  @override
  $AppDatabaseBuilderContract addMigrations(List<Migration> migrations) {
    _migrations.addAll(migrations);
    return this;
  }

  @override
  $AppDatabaseBuilderContract addCallback(Callback callback) {
    _callback = callback;
    return this;
  }

  @override
  Future<AppDatabase> build() async {
    final path = name != null
        ? await sqfliteDatabaseFactory.getDatabasePath(name!)
        : ':memory:';
    final database = _$AppDatabase();
    database.database = await database.open(
      path,
      _migrations,
      _callback,
    );
    return database;
  }
}

class _$AppDatabase extends AppDatabase {
  _$AppDatabase([StreamController<String>? listener]) {
    changeListener = listener ?? StreamController<String>.broadcast();
  }

  PianoDao? _pianoDaoInstance;

  AulaDao? _aulaDaoInstance;

  DispositivoDao? _dispositivoDaoInstance;

  Future<sqflite.Database> open(
    String path,
    List<Migration> migrations, [
    Callback? callback,
  ]) async {
    final databaseOptions = sqflite.OpenDatabaseOptions(
      version: 1,
      onConfigure: (database) async {
        await database.execute('PRAGMA foreign_keys = ON');
        await callback?.onConfigure?.call(database);
      },
      onOpen: (database) async {
        await callback?.onOpen?.call(database);
      },
      onUpgrade: (database, startVersion, endVersion) async {
        await MigrationAdapter.runMigrations(
            database, startVersion, endVersion, migrations);

        await callback?.onUpgrade?.call(database, startVersion, endVersion);
      },
      onCreate: (database, version) async {
        await database.execute(
            'CREATE TABLE IF NOT EXISTS `piano` (`id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, `nome` TEXT NOT NULL)');
        await database.execute(
            'CREATE TABLE IF NOT EXISTS `aula` (`id` INTEGER NOT NULL, `numero` INTEGER, `nome` TEXT NOT NULL, `tipo` TEXT NOT NULL, `mostra_sulla_mappa` INTEGER NOT NULL, `id_piano` INTEGER NOT NULL, FOREIGN KEY (`id_piano`) REFERENCES `piano` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE, PRIMARY KEY (`id`))');
        await database.execute(
            'CREATE TABLE IF NOT EXISTS `dispositivo` (`id` INTEGER NOT NULL, `nome` TEXT NOT NULL, `tipo` TEXT NOT NULL, `id_aula` INTEGER NOT NULL, FOREIGN KEY (`id_aula`) REFERENCES `aula` (`id`) ON UPDATE NO ACTION ON DELETE CASCADE, PRIMARY KEY (`id`))');

        await callback?.onCreate?.call(database, version);
      },
    );
    return sqfliteDatabaseFactory.openDatabase(path, options: databaseOptions);
  }

  @override
  PianoDao get pianoDao {
    return _pianoDaoInstance ??= _$PianoDao(database, changeListener);
  }

  @override
  AulaDao get aulaDao {
    return _aulaDaoInstance ??= _$AulaDao(database, changeListener);
  }

  @override
  DispositivoDao get dispositivoDao {
    return _dispositivoDaoInstance ??=
        _$DispositivoDao(database, changeListener);
  }
}

class _$PianoDao extends PianoDao {
  _$PianoDao(
    this.database,
    this.changeListener,
  )   : _queryAdapter = QueryAdapter(database),
        _pianoInsertionAdapter = InsertionAdapter(
            database,
            'piano',
            (Piano item) =>
                <String, Object?>{'id': item.id, 'nome': item.name}),
        _pianoDeletionAdapter = DeletionAdapter(
            database,
            'piano',
            ['id'],
            (Piano item) =>
                <String, Object?>{'id': item.id, 'nome': item.name});

  final sqflite.DatabaseExecutor database;

  final StreamController<String> changeListener;

  final QueryAdapter _queryAdapter;

  final InsertionAdapter<Piano> _pianoInsertionAdapter;

  final DeletionAdapter<Piano> _pianoDeletionAdapter;

  @override
  Future<List<Piano>> getAllPianos() async {
    return _queryAdapter.queryList('SELECT * FROM piano',
        mapper: (Map<String, Object?> row) =>
            Piano(id: row['id'] as int, name: row['nome'] as String));
  }

  @override
  Future<Piano?> getPianoById() async {
    return _queryAdapter.query('SELECT * FROM piano WHERE id = ?',
        mapper: (Map<String, Object?> row) =>
            Piano(id: row['id'] as int, name: row['nome'] as String));
  }

  @override
  Future<void> deleteAll() async {
    await _queryAdapter.queryNoReturn('DELETE FROM piano');
  }

  @override
  Future<void> insertPiano(Piano piano) async {
    await _pianoInsertionAdapter.insert(piano, OnConflictStrategy.replace);
  }

  @override
  Future<void> deletePiano(Piano post) async {
    await _pianoDeletionAdapter.delete(post);
  }
}

class _$AulaDao extends AulaDao {
  _$AulaDao(
    this.database,
    this.changeListener,
  )   : _queryAdapter = QueryAdapter(database),
        _aulaInsertionAdapter = InsertionAdapter(
            database,
            'aula',
            (Aula item) => <String, Object?>{
                  'id': item.id,
                  'numero': item.numero,
                  'nome': item.name,
                  'tipo': item.type,
                  'mostra_sulla_mappa': item.showsOnMap ? 1 : 0,
                  'id_piano': item.pianoId
                }),
        _aulaDeletionAdapter = DeletionAdapter(
            database,
            'aula',
            ['id'],
            (Aula item) => <String, Object?>{
                  'id': item.id,
                  'numero': item.numero,
                  'nome': item.name,
                  'tipo': item.type,
                  'mostra_sulla_mappa': item.showsOnMap ? 1 : 0,
                  'id_piano': item.pianoId
                });

  final sqflite.DatabaseExecutor database;

  final StreamController<String> changeListener;

  final QueryAdapter _queryAdapter;

  final InsertionAdapter<Aula> _aulaInsertionAdapter;

  final DeletionAdapter<Aula> _aulaDeletionAdapter;

  @override
  Future<List<Aula>> getAllAule() async {
    return _queryAdapter.queryList('SELECT * FROM aula',
        mapper: (Map<String, Object?> row) => Aula(
            id: row['id'] as int,
            numero: row['numero'] as int?,
            name: row['nome'] as String,
            type: row['tipo'] as String,
            showsOnMap: (row['mostra_sulla_mappa'] as int) != 0,
            pianoId: row['id_piano'] as int));
  }

  @override
  Future<Aula?> getAulaById() async {
    return _queryAdapter.query('SELECT * FROM aula WHERE id = ?',
        mapper: (Map<String, Object?> row) => Aula(
            id: row['id'] as int,
            numero: row['numero'] as int?,
            name: row['nome'] as String,
            type: row['tipo'] as String,
            showsOnMap: (row['mostra_sulla_mappa'] as int) != 0,
            pianoId: row['id_piano'] as int));
  }

  @override
  Future<List<Aula?>> getAuleByPiano() async {
    return _queryAdapter.queryList('SELECT * FROM aula WHERE id_piano = ?',
        mapper: (Map<String, Object?> row) => Aula(
            id: row['id'] as int,
            numero: row['numero'] as int?,
            name: row['nome'] as String,
            type: row['tipo'] as String,
            showsOnMap: (row['mostra_sulla_mappa'] as int) != 0,
            pianoId: row['id_piano'] as int));
  }

  @override
  Future<void> insertAula(Aula aula) async {
    await _aulaInsertionAdapter.insert(aula, OnConflictStrategy.replace);
  }

  @override
  Future<void> deleteAula(Aula post) async {
    await _aulaDeletionAdapter.delete(post);
  }
}

class _$DispositivoDao extends DispositivoDao {
  _$DispositivoDao(
    this.database,
    this.changeListener,
  )   : _queryAdapter = QueryAdapter(database),
        _dispositivoInsertionAdapter = InsertionAdapter(
            database,
            'dispositivo',
            (Dispositivo item) => <String, Object?>{
                  'id': item.id,
                  'nome': item.name,
                  'tipo': item.type,
                  'id_aula': item.aulaId
                }),
        _dispositivoDeletionAdapter = DeletionAdapter(
            database,
            'dispositivo',
            ['id'],
            (Dispositivo item) => <String, Object?>{
                  'id': item.id,
                  'nome': item.name,
                  'tipo': item.type,
                  'id_aula': item.aulaId
                });

  final sqflite.DatabaseExecutor database;

  final StreamController<String> changeListener;

  final QueryAdapter _queryAdapter;

  final InsertionAdapter<Dispositivo> _dispositivoInsertionAdapter;

  final DeletionAdapter<Dispositivo> _dispositivoDeletionAdapter;

  @override
  Future<List<Dispositivo>> getAllDispositivi() async {
    return _queryAdapter.queryList('SELECT * FROM dispositivo',
        mapper: (Map<String, Object?> row) => Dispositivo(
            id: row['id'] as int,
            name: row['nome'] as String,
            type: row['tipo'] as String,
            aulaId: row['id_aula'] as int));
  }

  @override
  Future<Dispositivo?> getDispositivoById() async {
    return _queryAdapter.query('SELECT * FROM dispositivo WHERE id = ?',
        mapper: (Map<String, Object?> row) => Dispositivo(
            id: row['id'] as int,
            name: row['nome'] as String,
            type: row['tipo'] as String,
            aulaId: row['id_aula'] as int));
  }

  @override
  Future<List<Dispositivo?>> getDispositiviByAula(int id_aula) async {
    return _queryAdapter.queryList(
        'SELECT * FROM dispositivo WHERE id_aula = ?1',
        mapper: (Map<String, Object?> row) => Dispositivo(
            id: row['id'] as int,
            name: row['nome'] as String,
            type: row['tipo'] as String,
            aulaId: row['id_aula'] as int),
        arguments: [id_aula]);
  }

  @override
  Future<void> insertDispositivo(Dispositivo dispositivo) async {
    await _dispositivoInsertionAdapter.insert(
        dispositivo, OnConflictStrategy.replace);
  }

  @override
  Future<void> deleteDispositivo(Dispositivo post) async {
    await _dispositivoDeletionAdapter.delete(post);
  }
}
