class Utente {
  int id;
  String email;
  String nome;
  String cognome;
  String token;
  DateTime dataCreazione;
  int idRuolo;

  Utente({
    required this.id,
    required this.email,
    required this.nome,
    required this.cognome,
    required this.token,
    required this.dataCreazione,
    required this.idRuolo,
  });

  // Convert a Utente into a JSON-formatted Map.
  Map<String, dynamic> toJson() => {
        'id': id,
        'email': email,
        'nome': nome,
        'cognome': cognome,
        'token': token,
        'data_creazione': dataCreazione.toIso8601String(),
        'id_ruolo': idRuolo,
      };

  // Create a Utente from a JSON-formatted Map.
  factory Utente.fromJson(Map<String, dynamic> json) => Utente(
        id: json['id'],
        email: json['email'],
        nome: json['nome'],
        cognome: json['cognome'],
        token: json['token'],
        dataCreazione: DateTime.parse(json['data_creazione']),
        idRuolo: json['id_ruolo'],
      );
}