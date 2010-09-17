<html>
  <head>
    <title>Brief</title>
    <link rel="stylesheet" href="/pim/static/letter.css">
  </head>
  <body>
    <div class="address">
    <?=$company->getName()?><br />
    <?=$company->getDepartment()?><br />
    <?=$company->getStreet()?> <?=$company->getHouseNumber()?> <br />
    <?=$company->getPostalCode()?> <?=$company->getCity()?>
    </div>
    <div class="letter">
    <p>Geachte Heer/Mevrouw,</p>

    <p>Op basis van artikel 35 van de Wet bescherming persoonsgegevens (Wbp)
    verneem ik graag of u persoonsgegevens van mij verwerkt. Indien dat het
    geval is, verzoek ik u om een volledig overzicht in begrijpelijke vorm, een
    omschrijving van de doeleinden van de verwerking, de herkomst van de
    gegevens en een overzicht van de organisaties aan wie u deze gegevens heeft
    verstrekt.</p>

    <p>Wellicht ten overvloede vermeld ik dat in lid 1 van het wetsartikel een
    termijn van vier weken wordt genoemd waarin u aan dit verzoek moet
    voldoen.</p>

    <p>Een kopie van mijn identiteitsbewijs is als bijlage opgenomen.</p>

    <p>Met vriendelijke groet,</p>

    <?=$firstname?> <?=$lastname?>

    </div>
  </body>
</html>
