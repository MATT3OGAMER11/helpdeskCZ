import 'dart:convert';

List<Segnalazione> SegnalazioneFromJson(String str) =>
    List<Segnalazione>.from(json.decode(str).map((x) => Segnalazione.fromJson(x)));

String SegnalazioneToJson(List<Segnalazione> data) =>
    json.encode(List<dynamic>.from(data.map((x) => x.toJson())));

class Segnalazione {
  int? id_aula;
  int? id_categoria;
  int? id_dispositivo;
  String testo_segnalazione;

  Segnalazione({
    required this.id_aula,
    required this.id_categoria,
    required this.id_dispositivo,
    required this.testo_segnalazione,
  });

  factory Segnalazione.fromJson(Map<String, dynamic> json) => Segnalazione(
        id_aula: json["id_aula"],
        id_categoria: json["id_categoria"],
        id_dispositivo: json["id_dispositivo"],
        testo_segnalazione: json["testo_segnalazione"],
      );

  Map<String, dynamic> toJson() => {
        "id_aula": id_aula,
        "id_categoria": id_categoria,
        "id_dispositivo": id_dispositivo,
        "testo_segnalazione": testo_segnalazione,
      };
}