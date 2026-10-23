(function (root) {
    'use strict';
    function buildDocument(data) {
        var content = [];
        data.groups.forEach(function (group, index) {
            var left = 'RÉPUBLIQUE DU CAMEROUN\nPaix – Travail – Patrie\nMINISTÈRE DE L’ENSEIGNEMENT SUPÉRIEUR\n' + data.campus + '\n' + data.adresse;
            var right = 'REPUBLIC OF CAMEROON\nPeace – Work – Fatherland\nMINISTRY OF HIGHER EDUCATION\n' + data.campus + '\n' + data.adresse;
            var header = [{text:left,width:'*',alignment:'center',fontSize:7}];
            header.push(data.logo ? {image:data.logo,fit:[55,45],width:65,alignment:'center'} : {text:'',width:40});
            header.push({text:right,width:'*',alignment:'center',fontSize:7});
            content.push({columns:header,pageBreak:index ? 'before' : undefined});
            content.push({text:'Procès-verbal d’admission\nAnnée académique : '+data.annee,alignment:'center',bold:true,fontSize:14,margin:[0,14,0,10]});
            content.push({text:'Cycle : '+data.cycle+'\nFilière : '+group.filiere+'\nSpécialité : '+group.specialite+' — Niveau : '+group.niveau+'\n'+data.session+'\nEffectif : '+group.effectif+' — Admis : '+group.rows.length,fontSize:9});
            content.push({text:'PV Admission',alignment:'center',bold:true,fontSize:12,margin:[0,14,0,12]});
            var body = [[ 'Rang','Matricule','Nom(s) et Prénom(s)','Moyenne /20','Crédit /'+group.credits,'Décision finale' ].map(function(text){return {text:text,bold:true,fillColor:'#ddd',alignment:'center'};})];
            group.rows.forEach(function(row){body.push(row.map(function(cell,i){return {text:String(cell == null ? '—' : cell),alignment:i===1||i===2?'left':'center'};}));});
            content.push({table:{headerRows:1,widths:[27,77,'*',55,43,58],body:body,dontBreakRows:true},fontSize:8,layout:{hLineWidth:function(){return .5;},vLineWidth:function(){return .5;},paddingTop:function(){return 3;},paddingBottom:function(){return 3;}}});
        });
        return {pageSize:'A4',pageOrientation:'portrait',pageMargins:[28,28,28,32],defaultStyle:{font:'Roboto'},content:content,
            footer:function(page,pages){return {text:page+' / '+pages,alignment:'center',fontSize:8,margin:[0,8,0,0]};}};
    }
    root.AdmissionPdf = {buildDocument:buildDocument};
    if(typeof module !== 'undefined') module.exports=root.AdmissionPdf;
}(typeof window !== 'undefined' ? window : global));
