import 'package:floor/floor.dart';
import '../table/model.dart';

@dao
abstract class PianoDao {
  @Insert(onConflict: OnConflictStrategy.replace)
  Future<void> insertPiano(Piano piano);

  @Query('SELECT * FROM piano')
  Future<List<Piano>> getAllPianos();

  @Query('SELECT * FROM piano WHERE id = ?')
  Future<Piano?> getPianoById();

  @Query('DELETE FROM piano')
  Future<void> deleteAll();

  @delete
  Future<void> deletePiano(Piano post);
}

@dao
abstract class AulaDao {
  @Insert(onConflict: OnConflictStrategy.replace)
  Future<void> insertAula(Aula aula);

  @Query('SELECT * FROM aula')
  Future<List<Aula>> getAllAule();

  @Query('SELECT * FROM aula WHERE id = ?')
  Future<Aula?> getAulaById();

  @Query('SELECT * FROM aula WHERE id_piano = ?')
  Future<List<Aula?>> getAuleByPiano();

  @delete
  Future<void> deleteAula(Aula post);
}

@dao
abstract class DispositivoDao {
  @Insert(onConflict: OnConflictStrategy.replace)
  Future<void> insertDispositivo(Dispositivo dispositivo);

  @Query('SELECT * FROM dispositivo')
  Future<List<Dispositivo>> getAllDispositivi();

  @Query('SELECT * FROM dispositivo WHERE id = ?')
  Future<Dispositivo?> getDispositivoById();

  @Query('SELECT * FROM dispositivo WHERE id_aula = :id_aula')
  Future<List<Dispositivo?>> getDispositiviByAula(int id_aula);

  

  @delete
  Future<void> deleteDispositivo(Dispositivo post);
}
