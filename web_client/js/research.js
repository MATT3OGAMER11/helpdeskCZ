function researchAula(aule) {
    let iClassi = document.getElementById('class');
    let iLab = document.getElementById('lab');
    let iUff = document.getElementById('uff');
    let iWc = document.getElementById('wc');
    let iAltre = document.getElementById('altreA');
    let auleFiltrate = [];

    if(iClassi.checked){
        aule.forEach(aula => {
            if(aula.tipo === 'classe'){
                auleFiltrate.push(aula);
            }
        });
    }
    if(iLab.checked){
        aule.forEach(aula => {
            if(aula.tipo === 'laboratorio'){
                auleFiltrate.push(aula);
            }
        });
    }
    if(iUff.checked){
        aule.forEach(aula => {
            if(aula.tipo === 'ufficio'){
                auleFiltrate.push(aula);
            }
        });
    }
    if (iWc.checked){
        aule.forEach(aula => {
            if(aula.tipo === 'bagno'){
                auleFiltrate.push(aula);
            }
        });
    }
    if (iAltre.checked){
        aule.forEach(aula => {
            if(aula.tipo !== 'classe' && aula.tipo !== 'laboratorio' && aula.tipo !== 'ufficio' && aula.tipo !== 'bagno'){
                auleFiltrate.push(aula);
            }
        });
    }
    insertCardAula(auleFiltrate);

}

function reserchAulaByName(aule){
    let input = document.getElementById('search');
    let auleFiltrate = [];
    aule.forEach(aula => {
        if(aula.nome.includes(input.value.toUpperCase())){
            auleFiltrate.push(aula);
        }
    });
    insertCardAula(auleFiltrate);
}
