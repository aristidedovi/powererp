<?php

require_once DOL_DOCUMENT_ROOT.'/includes/tecnickcom/tcpdf/tcpdf.php';
require_once DOL_DOCUMENT_ROOT.'/core/class/html.form.class.php';
dol_include_once('/salariescontracts/lib/salariescontracts.lib.php');
dol_include_once('/salariescontracts/common.inc.php');

global $conf, $mysoc, $langs;


$id 		= GETPOST('id', 'int');
$sc 		= new Salariescontracts($db);
$form 		= new Form($db);
$sal_usr 	= new User($db);

$sc->fetch($id);
$sal_usr->fetch($sc->fk_user);




// INFORMATIONS SOCIETE
$soc_manager 	= $conf->global->MAIN_INFO_SOCIETE_MANAGERS;
$soc_manager 	= ($soc_manager) ? $soc_manager : str_repeat(".", 50);
$soc_societe 	= $conf->global->MAIN_INFO_SOCIETE_NOM;
$soc_ville 		= $conf->global->MAIN_INFO_SOCIETE_TOWN;
$soc_adress 	= "".trim($langs->convToOutputCharset(dol_format_address($mysoc, 1, " ", $langs)))."";
$soc_currency	= $langs->getCurrencySymbol($conf->currency);




// INFORMATIONS SALARIE
$sal_nbrhour  	= number_format($sal_usr->weeklyhours);

if($sal_usr->salary){
	$sal_salaire  	= ($sal_usr->salary != '' ?price($sal_usr->salary, '', $langs, 1, -1, -1, strtolower($langs->transnoentitiesnoconv("Currency".$conf->currency))) : '');
	$sal_numword = '';
	$sal_numword .= $sc->numberToWordsFunction($sal_usr->salary, strtolower($langs->transnoentitiesnoconv("Currency".$conf->currency)), '');
	$sal_numword  = trim(strtolower($sal_numword));
	$sal_salaire  	.= " (".$sal_numword.")";
	// $sal_salaire  	= "2000 euros (deux mille euros)";
}else
	$sal_salaire  	= str_repeat(".", 50);
$sal_numsecsoc = '';
if(isset($sal_usr->array_options['options_salariescontractsusersoci']))
$sal_numsecsoc = $sal_usr->array_options['options_salariescontractsusersoci'];
$sal_numsecsoc 	= ($sal_numsecsoc) ? $sal_numsecsoc : str_repeat(".", 50);


$sal_adress 	= trim($langs->convToOutputCharset(dol_format_address($sal_usr, 1, ' ', $langs)));
$sal_c_debut 	= date('d/m/Y',$sc->start_date);
$sal_cc_debut 	= date('Y-m-d',$sc->start_date);
$sal_c_fin   	= $sc->end_date ? "".date('d/m/Y',$sc->end_date)."" : "____/____/______ ";
$sal_datenai   	= $sal_usr->birth ? date('d/m/Y',$sal_usr->birth) : "____/____/______ ";
$sal_contype 	= $sc->getContractTypeById($sc->type);
$sal_function 	= ($sal_usr->job) ? $sal_usr->job : str_repeat(".", 50);
$sal_function 	= "".$sal_function."";
$Agenre  = "";
if($sal_usr->gender != '') $Agenre = ($sal_usr->gender == "man") ? "Monsieur " : "Mlle. ";
$sal_username 	= $sal_usr->firstname." ".$sal_usr->lastname;
$sal_touser 	= $Agenre.$sal_username;
$dt1 = "";

if(!empty($sal_usr->dateemployment)) $dt1 = date('d/m/Y',$sal_usr->dateemployment);
$sal_udebut   = $dt1 ? "".$dt1."" : "____/____/______ ";




$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->Cell(0, 2,'',0,1,'C');


$brsp = '<br>';
$table = "";


$table .='<div style="background-color:#ddd;border:1px solid #000;">';
$table .='<h2 style="margin:0;text-align:center;font-size:16px;font-weight:initial;">CONTRAT DE TRAVAIL A DUREE INDERTERMINEE</h2>';
$table .='</div>';
$table .='<div style="clear:both"></div>';
$table .= str_repeat($brsp, 3);

$table .= "Faisant suite ?? nos diff??rents entretiens, nous avons le plaisir de vous informer de votre engagement selon les modalit??s d??finies au pr??sent contrat.";
$table .= str_repeat($brsp, 2);
$table .= "La pr??sente offre est valable dans un d??lai de 15 jours ?? compter de la date d???envoi figurant en ent??te du pr??sent contrat.";
$table .= str_repeat($brsp, 2);
$table .= "<b>Entre les soussign??s</b>";
$table .= str_repeat($brsp, 2);

// $table .= "SASU ".$soc_societe.", repr??sent??e par ".$soc_manager.", Directeur G??n??ral, ayant son si??ge social au ".$soc_adress.".";
$table .= "Soci??t?? ".$soc_societe."</b>, repr??sent??e par ".$soc_manager.", Directeur G??n??ral, ayant son si??ge social au ".$soc_adress.".";
$table .= str_repeat($brsp, 2);
$table .= "Ci-apr??s d??nomm??e ?? La Soci??t?? ??";
$table .= str_repeat($brsp, 2);
$table .= "<b>D???une part,</b>";
$table .= str_repeat($brsp, 2);
$table .= "Et ".$sal_touser.", n?? le ".$sal_datenai.", enregistr?? sous le num??ro de s??curit?? sociale ".$sal_numsecsoc.", demeurant ".$sal_adress.".";
$table .= str_repeat($brsp, 2);
$table .= "Ci-apr??s d??nomm?? ?? Le Salari?? ??";
$table .= str_repeat($brsp, 2);
$table .= "<b>D???autre part,</b>";
$table .= str_repeat($brsp, 2);
$table .= "Il a ??t?? convenu ce qui suit :";


$table .= str_repeat($brsp, 2);
$table .= "<b><u>ARTICLE 1 - EMPLOI ET QUALIFICATION</u></b>";
$table .= str_repeat($brsp, 2);
$table .= "Le Salari?? est engag?? ?? compter du ".$sal_c_debut." en qualit?? de ".$sal_function." sous la Direction de ".$soc_manager." ??? Directeur G??n??ral.";
$table .= str_repeat($brsp, 2);
$table .= "En fonction des n??cessit??s d'organisation du travail, la Soci??t?? pourra affecter le Salari?? aux divers postes de travail correspondant ?? la nature de son emploi.";
$table .= str_repeat($brsp, 2);
$table .= "".$sal_touser." d??clare formellement ??tre libre de tout engagement vis-??-vis de son pr??c??dent employeur ?? la date d??finie au 1er paragraphe ci-dessus et n?????tre li?? ?? aucune autre entreprise. En particulier, ".$sal_touser." d??clare formellement n?????tre tenu par aucune clause de non concurrence pouvant faire obstacle ?? la mise en ??uvre du pr??sent contrat.";
$table .= str_repeat($brsp, 2);
$table .= "La dur??e hebdomadaire du travail est fix??e ?? 39 heures par semaine et se repartit entre les jours de la semaine, conform??ment ?? l???horaire affich?? dans l???entreprise.";
$table .= str_repeat($brsp, 2);

$table .= "<b><u>ARTICLE 2 - OBJET ET DUREE DU CONTRAT</u></b>";
$table .= str_repeat($brsp, 2);
$table .= "Ce contrat est conclu pour une dur??e ind??termin??e ?? compter du ".$sal_c_debut.".";
$table .= str_repeat($brsp, 2);
$table .= "Il pourra toujours cesser ?? l'initiative de l'une ou l'autre des parties conform??ment aux dispositions l??gales et conventionnelles en vigueur.";
$table .= str_repeat($brsp, 2);

$table .= "<b><u>ARTICLE 3 - PERIODE D'ESSAI</u></b>";
$table .= str_repeat($brsp, 2);
$table .= "Le pr??sent contrat deviendra d??finitif ?? l???expiration d???une p??riode d???essai de 2 mois ?? compter de la date de prise des fonctions, fix??e le ".$sal_c_debut.". Pendant la p??riode d???essai, chacune des parties pourra mettre fin au pr??sent Contrat conform??ment ?? la loi applicable.";
$table .= str_repeat($brsp, 2);

$table .= "<b><u>ARTICLE 4 ??? DUREE DU TRAVAIL</u></b>";
$table .= str_repeat($brsp, 2);
$table .= "Le Salari?? est assujetti ?? une dur??e du travail de 39 heures correspondant ?? la dur??e l??gale de 35 heures ?? laquelle s???ajoutent 4 suppl??mentaires.";
$table .= str_repeat($brsp, 2);
$table .= "Il est pr??cis?? que la r??mun??ration du Salari?? vis??e ?? l???article 5, du pr??sent contrat comprend la r??mun??ration des heures effectu??es dans la limite de la dur??e l??gale du travail, plus 4 heures suppl??mentaires par semaine. En cons??quence, seules les heures suppl??mentaires effectu??es au-del?? de 4 heures suppl??mentaires donneront lieu ?? paiement major?? et/ou repos compensateur de remplacement.";
$table .= str_repeat($brsp, 2);
$table .= "Les heures suppl??mentaires ??ventuellement effectu??es au-del?? de cette dur??e, apr??s autorisation pr??alable ou demande expresse du sup??rieur hi??rarchique, seront soumises aux dispositions l??gales (majoration, repos compensateur de remplacement) et ce dans la limite du contingent annuel d???heures suppl??mentaire.";
$table .= str_repeat($brsp, 2);

$table .= "<b><u>ARTICLE 5 - REMUNERATION</u></b>";
$table .= str_repeat($brsp, 2);
$table .= "En r??mun??ration de ses services, le Salari?? percevra une r??mun??ration brute mensuelle de ".$sal_salaire.", payable en 12 mensualit??s ??gales comprenant d???ores et d??j?? 4 heures suppl??mentaires.";
$table .= str_repeat($brsp, 2);
$table .= "Le Salari?? b??n??ficiera en outre des avantages sociaux consentis au personnel de sa cat??gorie, notamment, en ce qui concerne les remboursements de frais de missions et d??placements et le r??gime de retraite et de pr??voyance.";
$table .= str_repeat($brsp, 2);
$table .= "<b><u>ARTICLE 6 - LIEU DE TRAVAIL</u></b>";
$table .= str_repeat($brsp, 2);
$table .= "Le lieu de travail, ?? la date de conclusion du pr??sent contrat, est situ?? au ".$soc_adress.".";
$table .= str_repeat($brsp, 2);
$table .= "Cependant, compte tenu de la nature de ses fonctions et de la particularit?? de l???activit?? de la Soci??t??, le Salari?? pourra ??tre amen?? ?? effectuer des missions de plus ou moins longue dur??e chez des clients de la Soci??t??, situ??s en France M??tropolitaine.";
$table .= str_repeat($brsp, 2);

$table .= "<b><u>ARTICLE 7 - CONGES PAYES</u></b>";
$table .= str_repeat($brsp, 2);
$table .= "Le Salari?? b??n??ficie d'un cong?? annuel pay??, conform??ment aux dispositions en vigueur dans l'??tablissement.";
$table .= str_repeat($brsp, 2);
$table .= "Les modalit??s de ce cong?? seront d??termin??es, par accord avec la direction, compte tenu des n??cessit??s de service.";
$table .= str_repeat($brsp, 2);
$table .= "La p??riode des cong??s sera d??termin??e par d??cision de l'employeur qui sera port??e en temps utile ?? la connaissance du personnel. Par mesure d???organisation et selon les services, nous demandons ?? recevoir votre demande de cong??s au moins et au minimum un mois avant celui-ci de fa??on ?? ce qu???il soit valid?? par vos sup??rieurs.";
$table .= str_repeat($brsp, 2);

$table .= "<b><u>ARTICLE 8 - ABSENCES/REMPLACEMENT</u></b>";
$table .= str_repeat($brsp, 2);
$table .= "En cas d???emp??chement d???exercer son activit?? et d???accomplir ses obligations, le Salari?? devra en aviser la Soci??t?? dans les 48 heures en indiquant les motifs et la dur??e probable de cet emp??chement et en lui adressant un certificat m??dical.";
$table .= str_repeat($brsp, 2);
$table .= "Le non-respect par le Salari?? de cette obligation pourra entra??ner la rupture du contrat de travail ?? ses torts.";
$table .= str_repeat($brsp, 2);
$table .= "En tout ??tat de cause, le Salari?? mettra tout en ??uvre pour avertir ne serait-ce que par t??l??phone, SMS ou courriel dans un premier temps, l???entreprise de son absence.";
$table .= str_repeat($brsp, 2);
$table .= "Afin de pallier l?????ventuelle absence du Salari?? et afin d?????viter tout dysfonctionnement de l???activit??, la Soci??t?? se r??serve la facult?? de proc??der au remplacement provisoire du Salari?? dans ses fonctions par toute personne de son choix.";
$table .= str_repeat($brsp, 2);

$table .= "<b><u>ARTICLE 9 - EXCLUSIVITE DES SERVICES</u></b>";
$table .= str_repeat($brsp, 2);
$table .= "Pendant toute la dur??e du pr??sent contrat, le Salari?? devra r??server ?? la Soci??t?? l???exclusivit?? de ses services et ne pourra avoir aucune autre activit?? professionnelle, pour son compte, pour le compte d???une autre entreprise m??me non-concurrente, ou pour le compte d???un tiers, sauf accord ??crit pr??alable de la Direction.";
$table .= str_repeat($brsp, 2);
$table .= "Dans une telle hypoth??se, le Salari?? s???engage ?? respecter les limites l??gales en mati??re de dur??e du travail.";
$table .= str_repeat($brsp, 2);

$table .= "<b><u>ARTICLE 10 - RESPECT DES BIENS, DROITS ET INTERETS DE L???ENTREPRISE</u></b>";
$table .= str_repeat($brsp, 2);
$table .= "Le Salari?? s???engage ?? exercer ses activit??s, et notamment ?? utiliser les supports, informations et mat??riels qui lui seront remis par l???entreprise ?? cet effet, dans le respect des droits de l???entreprise et en conformit?? avec ses int??r??ts.";
$table .= str_repeat($brsp, 2);
$table .= "Les biens de toute nature qui sont remis au Salari?? pour l???ex??cution de ses fonctions ne sont d??tenus par lui qu????? titre pr??caire.";
$table .= str_repeat($brsp, 1);
$table .= "Le Salari?? est responsable de leur maintien en parfait ??tat.";
$table .= str_repeat($brsp, 2);
$table .= "Le Salari?? ne peut ni les c??der, ni les pr??ter, ni les louer ?? des tiers.";
$table .= str_repeat($brsp, 1);
$table .= "La Soci??t?? se r??serve le droit de contr??ler cette obligation ?? tout moment.";
$table .= str_repeat($brsp, 2);
$table .= "Le Salari?? devra ??tre en mesure de remettre ?? l???entreprise, ?? la premi??re demande de l???un de ses repr??sentants, tous biens, mat??riels, documents, tarifs, programmes, instructions, fichiers lui appartenant qu???elle aurait mis ?? sa disposition.";
$table .= str_repeat($brsp, 2);
$table .= "Le Salari?? s???interdit de fa??on expresse d???utiliser ou de reproduire ?? des fins personnelles, ni pendant, ni ?? l???issue de son contrat de travail, la documentation, les logiciels ou toutes informations, sous quelque forme que ce soit, appartenant ?? l???entreprise dont il aura connaissance du fait de sa pr??sence dans l???entreprise ou chez les clients de cette derni??re.";
$table .= str_repeat($brsp, 2);

$table .= "<b><u>ARTICLE 11 - SECRET PROFESSIONNEL ET OBLIGATION DE DISCRETION </u></b>";
$table .= str_repeat($brsp, 2);
$table .= "Pendant la dur??e et apr??s la rupture de son contrat de travail, le Salari?? est tenu, ind??pendamment d???une obligation de r??serve g??n??rale, ?? une discr??tion absolue sur tous les faits dont il pourrait avoir connaissance du fait de sa pr??sence dans l???entreprise ou chez les clients de cette derni??re.";
$table .= str_repeat($brsp, 2);
$table .= "Cette obligation de r??serve concerne plus particuli??rement la gestion et le fonctionnement de la Soci??t?? ainsi que la situation financi??re et les projets relatifs ?? ses clients et fournisseurs.";
$table .= str_repeat($brsp, 2);
$table .= "Les documents ou rapports que le Salari?? pourra ??tablir dans le cadre de ses fonctions ou dont la communication lui sera donn??e, demeureront la propri??t?? de la Soci??t??. En cons??quence, le Salari?? ne pourra ni en conserver de copies ou de photocopies, ni en donner communication ?? des tiers, sans l???accord ??crit de la Soci??t??.";
$table .= str_repeat($brsp, 2);
$table .= "Toute violation par le Salari?? des dispositions susvis??es entrainera sont licenciement pour faute grave, sans pr??avis ni indemnit?? nonobstant les dommages et int??r??ts qui pourront lui ??tre demand??es en justice.";
$table .= str_repeat($brsp, 2);

$table .= "<b><u>ARTICLE 12 ??? CESSATION DU CONTRAT DE TRAVAIL</u></b>";
$table .= str_repeat($brsp, 2);
$table .= "<b>12.1 Pr??avis</b>";
$table .= str_repeat($brsp, 1);
$table .= "Hormis le cas de force majeure, de faute grave ou lourde le pr??sent contrat ne pourra ??tre rompu qu???apr??s avoir respect?? un pr??avis r??ciproque de deux mois.";
$table .= str_repeat($brsp, 2);
$table .= "<b>12.2 Restitution des supports d???activit??</b>";
$table .= str_repeat($brsp, 1);
$table .= "Le Salari?? s???engage ?? restituer ?? la Soci??t??, ?? l???issue de son contrat de travail, tous biens ou objets qui lui sont confi??s par la Soci??t?? (tels que documents, pi??ces, outillages, ??quipements???) spontan??ment et en tout cas ?? la premi??re demande formul??e par un repr??sentant de l???entreprise.";
$table .= str_repeat($brsp, 2);
$table .= "Dans l???hypoth??se o?? le Salari?? ne d??f??rait pas ?? cette demande, la Soci??t?? se r??servera le droit d???appliquer une compensation financi??re entre la valeur des biens qui lui appartiennent et les sommes qui lui seront vers??es au titre du solde de tout compte, sans pr??judice d?????ventuelles poursuites civiles et/ou p??nales.";
$table .= str_repeat($brsp, 2);

$table .= "<b><u>ARTICLE 13 ??? INTERDICTION DE DEBAUCHAGE </u></b>";
$table .= str_repeat($brsp, 2);
$table .= "Pendant une p??riode de deux ann??es apr??s la rupture de son contrat de travail, qu???elle qu???en soit la cause, il est fait interdiction ?? ".$sal_touser." d???embaucher, directement ou indirectement, pour son compte ou pour le compte d???un tiers, des salari??s de la Soci??t?? ".$soc_societe.".";
$table .= str_repeat($brsp, 2);
$table .= "En cas d???infraction aux dispositions du pr??sent article, le Salari?? sera redevable ?? l?????gard de la Soci??t??, ?? titre de clause p??nale, d???une indemnit?? ??gale ?? douze fois le dernier salaire mensuel brut de la personne ainsi d??bauch??e.";
$table .= str_repeat($brsp, 2);

$table .= "<b><u>ARTICLE 14 - MUTUELLE </u></b>";
$table .= str_repeat($brsp, 2);
$table .= "La soci??t?? ".$soc_societe." adh??re ?? une mutuelle obligatoire pour l???ensemble du personnel dont l???entreprise participe ?? hauteur de 50 %, le contrat et les conditions sont remis au Salari?? d??s l???embauche.";
$table .= str_repeat($brsp, 2);
$table .= "Pour tous refus ?? l???adh??sion, le Salari?? doit remettre au plus vite une attestation de Mutuelle Valide.";
$table .= str_repeat($brsp, 2);

$table .= "<b><u>ARTICLE 15 ??? INDEPENDANCE DES CLAUSES</u></b>";
$table .= str_repeat($brsp, 2);
$table .= "Si l???une ou plusieurs dispositions du pr??sent contrat devaient ??tre annul??es ou d??clar??es sans effet, il n???en r??sulterait pas, pour autant, la nullit?? de l???ensemble de la convention ou d???une ou plusieurs de ses autres dispositions.";
$table .= str_repeat($brsp, 3);


$table .= "<table border='0' cellpadding='9px'>";
$table .= "<tr>";
$table .= "<td> </td>";
$table .= "<td>";
$table .= "Fait ?? ".$soc_ville;
$table .= str_repeat($brsp, 1);
$table .= "Le ".date("d/m/Y");
$table .= str_repeat($brsp, 1);
$table .= "</td>";
$table .= "</tr>";

$table .= "<tr>";
$table .= "<td></td>";
$table .= "<td>";
$table .= "Pour la soci??t?? ".$soc_societe."";
$table .= "</td>";
$table .= "</tr>";

$table .= "<tr>";
$table .= "<td>";
$table .= "<b>".$sal_username."</b>";
$table .= "</td>";
$table .= "<td>";
$table .= "<b>".$soc_manager."</b>";
$table .= "</td>";
$table .= "</tr>";
$table .= "</table>";

$table .= str_repeat($brsp, 8);
$table .= '<span style="font-size:11px">';
$table .= "Signature pr??c??d??e de la mention";
$table .= str_repeat($brsp, 1);
$table .= "?? Lu et approuv?? ??";
$table .= '</span>';


$tbl = <<<EOF
    $table
EOF;
$pdf->writeHTML($tbl, true, false, true, false, '');

$pdf->Output('contrat_'.$ugenre.'_'.$sal_username.'.pdf', 'I');