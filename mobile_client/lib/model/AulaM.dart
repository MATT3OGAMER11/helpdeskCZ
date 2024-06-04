import 'dart:convert';

List<AulaM> aulaFromJson(String str) =>
    List<AulaM>.from(json.decode(str).map((x) => AulaM.fromJson(x)));

String aulaToJson(List<AulaM> data) =>
    json.encode(List<dynamic>.from(data.map((x) => x.toJson())));

class AulaM {
  int id;
  int? numero;
  String nome;
  String tipo;
  int mostraSullaMappa;
  int idPiano;

  AulaM({
    required this.id,
    required this.numero,
    required this.nome,
    required this.tipo,
    required this.mostraSullaMappa,
    required this.idPiano,
  });

  factory AulaM.fromJson(Map<String, dynamic> json) => AulaM(
        id: json["id"],
        numero: json["numero"],
        nome: json["nome"],
        tipo: json["tipo"],
        mostraSullaMappa: json["mostra_sulla_mappa"],
        idPiano: json["id_piano"],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "numero": numero,
        "nome": nome,
        "tipo": tipo,
        "mostra_sulla_mappa": mostraSullaMappa,
        "id_piano": idPiano,
      };
}
