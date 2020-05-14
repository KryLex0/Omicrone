<div class='container' style=" width: 100%; max-width: 7in; margin: 3px auto;">
    <div class='form-style-5'>
         <header>
    <h1  style = "text-align:center">FACTURE <h2>Omicrone</h2></h1>
  </header>
    <section class="flex">
        <dl>
            <dt>Facture # :</dt><dd><?php echo $num_facture; ?></dd>
            <dt>Date de facturation : </dt><dd> <?php echo $unefacture->getdatef(); ?></dd>
            <?php
            if(preg_match("/^[1-9][2][0]/", $leMois)){
              $leMois = "0".$leMois;
              $mois = substr($leMois, 0, 2);
              $annee = substr($leMois, 2, 4);
            }else{}


            ?>
            <dt>Facture du : </dt><dd><?php echo $mois.' / '.$annee; ?></dd>
        </dl>
    </section>
  <section class="flex">
    <dl class="bloc">
      <dt>Facturé au client :</dt>
      <dd>
        <?php echo $uncontrat->getcleclient()->getraison_social_client(); ?><br>
        <?php echo $uncontrat->getcleclient()->getadresse_client(); ?><br>
        <?php echo $uncontrat->getcleclient()->getville_client().', '. $uncontrat->getcleclient()->getcode_postal_client()?>
            <dl>
                <dt>SIRET</dt>
                <dd><?php echo $uncontrat->getcleclient()->getsiret_client(); ?></dd>
                <dt>Téléphone</dt>
                <dd><?php echo $uncontrat->getcleclient()->getclecontact()->gettel(); ?></dd>
                <dt>Email </dt>
                <dd><?php echo $uncontrat->getcleclient()->getclecontact()->getemail(); ?></dd>
            </dl>
      </dd>
    </dl>
    <dl class="bloc">
      <dt>Le consultant:</dt>
      <dd><?php echo $uncontrat->getcleconsultant()->getNom().' '.$uncontrat->getcleconsultant()->getPrenom()?><br>
      <?php echo $uncontrat->getcleconsultant()->getAdresse();?><br>
      <?php echo $uncontrat->getcleconsultant()->getVille(). ', '.$uncontrat->getcleconsultant()->getCp()?><br><br>
      Email : <?php echo$uncontrat->getcleconsultant()->getEmail(); ?>
      </dd>
      <dt>Période totale:</dt>
      <dd>Du <?php echo $datedebut = date('d/m/Y', strtotime($uncontrat->getdate_debut_contrat())) . ' au ' . $datefin = date('d/m/Y', strtotime($uncontrat->getdate_fin_contrat())); ?></dd>
    </dl>
  </section>
    <table>
        <thead>
        <tr>
            <th>Mission</th>
            <th>Jour de travail</th>
            <th>TJM</th>
            <th>Salaire</th>
            <th>Montant</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?php echo  $uncontrat->getmission();?></td>
            <td><?php echo $JF; ?></td>
            <td><?php  if( $uncontrat->getcleconsultant()->gettarif() <> 0 ){ echo $uncontrat->getcleconsultant()->gettarif(); } else { echo '-'; }?></td>
            <td><?php  if($uncontrat->getsalaire() <> 0){ echo $uncontrat->getsalaire(); } else {echo '-';} ?></td>
            <td><?php  echo $unefacture->getmontant(); ?>€</td>
        </tr>
        </tbody>
        <tfoot>
          <tr>
          <td colspan="3"></td>
          <td>TVA :</td>
          <td>20%</td>
          </tr>
          <tr>
          <td colspan="3"></td>
          <td>TTC :</td>
          <td><?php  echo $unefacture->MontantAvecTVA(); ?>€</td>
          </tr>
        <tr>
            <td colspan="3">− Faire les chèques payable à l'ordre de ... −</td>
            <td>Total:</td>
            <td><?php echo $unefacture->MontantAvecTVA(); ?>€</td>
        </tr>
        </tfoot>
    </table>
  <footer style = "text-align:center">
    <p>Moyen de paiement : chèque, virement bancaire</p>
    <p>Délai de réglement : 30 jours</p>
  </footer>

</div>
</div>

<style>
/*** @media all  ***/
* {
  box-sizing: border-box;
}
html {
  height: 100%;
}
body {
  min-height: 100%;
  margin: 0;
  display: flex;
  flex-flow: column nowrap;
  justify-content: center;
  align-items: sretch;
  font: 12pt/1.5 'Raleway', 'Cambria', sans-serif;
  font-weight: 300;
  background: #fff;
  color: #666;
  -webkit-print-color-adjust: exact;
}
header {
  padding: 16px;
  position: relative;
  color: #888;
}
header h1,
header h2 {
  font-weight: 200;
  margin: 0;
}
header h1 {
  font-size: 27pt;
  letter-spacing: 4px;
}
/* body > * {
  width: 100%;
  max-width: 7in;
  margin: 3px auto;
  /* background: #f0f0f0;
  text-align: center;*/


footer {
  padding: 16px;
}
footer p {
  font-size: 9pt;
  margin: 0;
  font-family: 'Nunito';
  color: #777;
}
section,
table {
  padding: 8px 0;
  position: relative;
}
dl {
  margin: 0;
  letter-spacing: -4px;
}
dl dt,
dl dd {
  letter-spacing: normal;
  display: inline-block;
  margin: 0;
  padding: 0px 6px;
  vertical-align: top;
}
dl.bloc > dt,
dl:not(.bloc) dt:not(:last-of-type),
dl:not(.bloc) dd:not(:last-of-type) {
  border-bottom: 1px solid #ddd;
}
dl:not(.bloc) dt {
  border-right: 1px solid #ddd;
}
dt {
  width: 49%;
  text-align: right;
  letter-spacing: 1px !important;
  overflow: hidden;
}
dd {
  width: 49%;
  text-align: left;
}
dd,
tr>td {
  font-family: 'Nunito';
}
section.flex {
  display: flex;
  flex-flow: row wrap;
  padding: 8px 16px;
  justify-content: space-around;
}
dl.bloc {
  padding: 0;
  flex: 1;
  vertical-align: top;
  min-width: 240px;
  margin: 0 8px 8px;
}
dl.bloc>dt {
  text-align: left;
  width: 100%;
  margin-top: 12px;
}
dl.bloc>dd {
  text-align: left;
  width: 100%;
  padding: 8px 0 5px 16px;
  line-height: 1.25;
}
dl.bloc>dd>dl dt {
  width: 33%;
}
dl.bloc>dd>dl dd {
  width: 60%;
}
dl.bloc dl {
  margin-top: 12px;
}
dl.bloc dd {
  font-size: 11pt;
}
table {
  width: 100%;
  padding: 0;
  border-spacing: 0px;
}
table tr {
  margin: 0;
  padding: 0;
  background: #fdfdfd;
  border-right: 1px solid #ddd;
  width: 100%;
}
table tr td,
table tr th {
  border: 1px solid #e3e3e3;
  border-top: 1px solid #fff;
  border-left-color: #fff;
  font-size: 11pt;
  background: #fdfdfd;
}
table thead th {
  background: #e9e9e9;
  background: linear-gradient(to bottom, #f9f9f9, #e9e9e9) !important;
  font-weight: 300;
  letter-spacing: 1px;
  padding: 15px 0 5px;
/*&:not(:last-child)*/
  border: none !important;
}
table tbody tr:last-child td {
  border-bottom: 1px solid #ddd;
}
table tbody td {
  min-width: 75px;
  padding: 3px 6px;
  line-height: 1.25;
}
table tfoot tr td {
/*border 1px solid #e3e3e3
      border-top 1px solid white
      border-left-color #fff*/
  height: 40px;
  padding: 6px 0 0;
  color: #000;
  text-shadow: 0 0 1px rgba(0,0,0,0.25);
  font-family: 'Cambria', 'Raleway', sans-serif;
  font-weight: 400;
  letter-spacing: 1px;
}
table tfoot tr td:first-child {
  font-style: italic;
  color: #997b7b;
}
a {
  color: #992c2c;
}
a:hover {
  color: #b00;
}
@page {
  margin: 0.5cm;
}
/*** @media screen  ***/
html,
body {
  background: #c4c4c4
}
header:before {
  content: '';
  position: absolute;
  top: 0;
  right: 0;
  border-top: 12px solid #333;
  border-left: 12px solid #ddd;
  width: 0;
  box-shadow: 1px 1px 2px rgba(0,0,0,0.18);
}

</style>
