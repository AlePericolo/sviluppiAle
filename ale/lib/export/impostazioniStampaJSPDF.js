function getStrutturaBase(){

    return {
        //generali
        'nomeFile' : 'Elenco',
        'foglio' : 'A4',
        'orientamento' : 'L',
        'tema': 'striped',
        //header
        'header': true,
        'headerFontSize': 9,
        'headerMargineSinistro': 12,
        'headerMargineAlto': 8,
        //margini
        'margineSinistro' : 12,
        'margineAlto' : 15,
        'margineDestro' : 12,
        'margineBasso' : 15,
        //font
        'dimensioneFont' : 9,
        'tipoFont': 'helvetica',
        'stileFont' : 'normal',
        //celle
        'paddingCella' : 1,
        'testoCella' : 'linebreak',
        'larghezzaCella' : 'auto',
        'pageBreak': 'avoid'
    };
}

function definisciOrientamento(orientamento, foglio){

    if(orientamento === 'L'){
        if(foglio === 'A3'){
            return new jsPDF(orientamento, 'mm', [420, 297]);
        }
        if(foglio === 'A4'){
            return new jsPDF(orientamento, 'mm', [297, 210]);
        }
    }
    if(orientamento === 'P'){
        if(foglio === 'A3'){
            return  new jsPDF(orientamento, 'mm', [297, 420]);
        }
        if(foglio === 'A4'){
            return new jsPDF(orientamento, 'mm', [210, 297]);
        }
    }
}

function definisciLarghezzaPagina(orientamento, foglio){

    if(orientamento === 'L'){
        if(foglio === 'A3'){
            return 420;
        }
        if(foglio === 'A4'){
            return  297;
        }
    }
    if(orientamento === 'P'){
        if(foglio === 'A3'){
            return  297;
        }
        if(foglio === 'A4'){
            return 210;
        }
    }
}

function srutturaStampa(stampa, larghezzaPagina){

    return {
        theme: stampa.tema,
        pageBreak: stampa.pageBreak, // 'auto', 'avoid'
        tableWidth: larghezzaPagina - (stampa.margineSinistro + stampa.margineDestro), // 'auto', 'wrap' or a number, A4:3508*2480 A3:4134*2923
        margin: {
            left: stampa.margineSinistro,
            top: stampa.margineAlto,
            right: stampa.margineDestro,
            bottom: stampa.margineBasso
        },

        styles: {
            cellPadding: stampa.paddingCella,
            font: stampa.tipoFont,
            fontSize: stampa.dimensioneFont,
            fontStyle: stampa.stileFont,
            overflow: stampa.testoCella,
            columnWidth: stampa.larghezzaCella
        }
    };
}

function srutturaStampaColumnCustom(stampa, larghezzaPagina, larghezzaColonna, numColonne){

    return {
        theme: stampa.tema,
        pageBreak: stampa.pageBreak, // 'auto', 'avoid'
        tableWidth: larghezzaPagina - (stampa.margineSinistro + stampa.margineDestro), // 'auto', 'wrap' or a number, A4:3508*2480 A3:4134*2923

        margin:{
            left: stampa.margineSinistro,
            top: stampa.margineAlto,
            right: stampa.margineDestro,
            bottom: stampa.margineBasso
        },

        styles: {
            cellPadding: stampa.paddingCella,
            font: stampa.tipoFont,
            fontSize: stampa.dimensioneFont,
            fontStyle: stampa.stileFont,
            overflow: stampa.testoCella,
            columnWidth: stampa.larghezzaCella
        },

        columnStyles: {
            0: { columnWidth: larghezzaColonna },
            1: { columnWidth: larghezzaColonna },
            2: { columnWidth: larghezzaColonna },
            3: { columnWidth: larghezzaColonna },
            4: { columnWidth: larghezzaColonna },
            5: { columnWidth: larghezzaColonna },
            6: { columnWidth: larghezzaColonna },
            7: { columnWidth: larghezzaColonna }
        }
    }
}
