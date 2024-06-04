class Categoria {
  int id;
  String nome;
  int id_ruolo;

  Categoria({
    required this.id,
    required this.nome,
    required this.id_ruolo,
  });

  // Convert a Categoria object into a Map. The keys must correspond to the names of the
  // JSON fields in the Categoria API.
  Map<String, dynamic> toJson() => {
    'id': id,
    'nome': nome,
    'id_ruolo': id_ruolo,
  };

  // Create a Categoria object from a Map. The keys must correspond to the names of the
  // JSON fields in the Categoria API.
  factory Categoria.fromJson(Map<String, dynamic> json) => Categoria(
    id: json['id'],
    nome: json['nome'],
    id_ruolo: json['id_ruolo'],
  );
}