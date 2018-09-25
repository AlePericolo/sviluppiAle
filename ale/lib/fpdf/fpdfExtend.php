<?php
/**
 * Created by PhpStorm.
 * User: marco
 * Date: 27/02/18
 * Time: 12.43
 */

require_once "../../lib/fpdf/fpdf.php";
require_once "../../src/model/Anagrafiche.php";

use Click\Affitti\TblBase\Anagrafiche;

class fpdfExtend extends FPDF
{

    const EURO = "€";
    const EURO_VAL = 6.55957;


    /** @var string */
    protected $orientamento;

    /** @var bool */
    protected $stampaHeader;

    /** @var bool */
    protected $stampaFooter;

    /** @var int */
    protected $numeroPagina;

    /** @var int */
    protected $numeroPaginaTotale;

    /** @var string */
    protected $dataFooter;


    /**
     * fpdfExtend constructor.
     */
    public function __construct($orientation = 'P', $size = 'A4', $stampaHeader = true, $stampaFooter = true, $unit = 'mm')
    {
        parent::__construct($orientation, $unit, $size);
        $this->stampaHeader = $stampaHeader;
        $this->stampaFooter = $stampaFooter;
        $this->orientamento = $orientation;
        $this->valorizzaTabellaConfigurazioni();

    }

    protected function getMese($mese)
    {
        $mesi = [
            'Gennaio',
            'Febbraio',
            'Marzo',
            'Aprile',
            'Maggio',
            'Giugno',
            'Luglio',
            'Agosto',
            'Settembre',
            'Ottobre',
            'Novembre',
            'Dicembre'
        ];
        return $mesi[$mese - 1];
    }


    /**
     * @param $agenzia Anagrafiche
     */
    public function headerAgenzia($agenzia)
    {
        if ($this->stampaHeader) {

            $this->Image('../../grafica/img/Logo-Click.png');

            $this->SetTextColor(NERO[0], NERO[1], NERO[2]);
            $this->SetDrawColor(GRIGIO_CHIARO[0], GRIGIO_CHIARO[1], GRIGIO_CHIARO[2]);
            $this->SetFont('Arial', '', 8);

            if ($this->orientamento == 'P') {
                $this->SetXY(90, 10);
                // Titolo in riquadro
                $this->MultiCell(110, 20, '', 1, 'L');
                $this->Text(92, 13, $agenzia->getRagioneSociale());

                $appIndirizzo = json_decode($agenzia->getIndirizzi())[0];
                $indirizzo =
                    $appIndirizzo->via . ' ' .
                    $appIndirizzo->civico;
                $citta =
                    $appIndirizzo->citta . ' ' .
                    $appIndirizzo->cap . ' ';
                if (strlen($appIndirizzo->provincia) > 0)
                    $citta .=
                        '(' . $appIndirizzo->provincia . ')';
                if (strlen($appIndirizzo->frazione) > 0) {
                    $citta .= ' Fraz. ' . $appIndirizzo->frazione;
                }

                $this->Text(92, 18, $indirizzo);
                if (strlen($citta) > 0)
                    $this->Text(92, 21, $citta);

                $appTelefono = json_decode($agenzia->getTelefoni())[0];
                $appCellulare = json_decode($agenzia->getCellulari())[0];
                $appEmail = json_decode($agenzia->getEmail())[0];

                if (strlen($appTelefono->telefono) > 0)
                    $this->Text(92, 24, 'Telefono : ' . $appTelefono->telefono);
                if (strlen($appCellulare->cellulare) > 0)
                    $this->Text(150, 24, 'Cellulare : ' . $appCellulare->cellulare);
                if (strlen($appEmail->email) > 0)
                    $this->Text(92, 27, 'e-Mail : ' . $appEmail->email);
            }
            else{
                $this->SetXY(170, 10);
                // Titolo in riquadro
                $this->MultiCell(110, 20, '', 1, 'L');
                $this->Text(172, 13, $agenzia->getRagioneSociale());

                $appIndirizzo = json_decode($agenzia->getIndirizzi())[0];
                $indirizzo =
                    $appIndirizzo->via . ' ' .
                    $appIndirizzo->civico;
                $citta =
                    $appIndirizzo->citta . ' ' .
                    $appIndirizzo->cap . ' ';
                if (strlen($appIndirizzo->provincia) > 0)
                    $citta .=
                        '(' . $appIndirizzo->provincia . ')';
                if (strlen($appIndirizzo->frazione) > 0) {
                    $citta .= ' Fraz. ' . $appIndirizzo->frazione;
                }

                $this->Text(172, 18, $indirizzo);
                if (strlen($citta) > 0)
                    $this->Text(172, 21, $citta);

                $appTelefono = json_decode($agenzia->getTelefoni())[0];
                $appCellulare = json_decode($agenzia->getCellulari())[0];
                $appEmail = json_decode($agenzia->getEmail())[0];

                if (strlen($appTelefono->telefono) > 0)
                    $this->Text(172, 24, 'Telefono : ' . $appTelefono->telefono);
                if (strlen($appCellulare->cellulare) > 0)
                    $this->Text(230, 24, 'Cellulare : ' . $appCellulare->cellulare);
                if (strlen($appEmail->email) > 0)
                    $this->Text(172, 27, 'e-Mail : ' . $appEmail->email);
            }
            // Interruzione di linea
            $this->Ln(5);
        }
    }


    public function footerAgenzia()
    {
        if (!$this->stampaFooter) return;

        if ($this->dataFooter == null) {
            $this->dataFooter =
                date('d') . ' ' .
                $this->getMese(date('m')) . ' ' .
                date('Y');
        }

        if ($this->numeroPagina == null)
            $this->numeroPagina = $this->PageNo();

        if ($this->numeroPaginaTotale == null)
            $this->numeroPaginaTotale = $this->numeroPagina;

        $this->SetY(-10);
        $this->SetFont('Arial', 'I', 8);

        // Stampa il numero di pagina centrato
        if ($this->orientamento == 'P') {
            $this->Cell(60, 8, $this->dataFooter, 0, 0, 'L');
            $this->Cell(60, 8, 'Click Affitti - CLICK SRL', 0, 0, 'C');
            $this->Cell(70, 8, 'Pagina ' . $this->numeroPagina . ' / ' . $this->numeroPaginaTotale, 0, 0, 'R');
        } else {
            $this->Cell(95, 8, $this->dataFooter, 0, 0, 'L');
            $this->Cell(95, 8, 'Click Affitti - CLICK SRL', 0, 0, 'C');
            $this->Cell(80, 8, 'Pagina ' . $this->numeroPagina . ' / ' . $this->numeroPaginaTotale, 0, 0, 'R');
        }
    }


    public function rotate($angle, $x = -1, $y = -1)
    {
        if ($x == -1)
            $x = $this->x;
        if ($y == -1)
            $y = $this->y;
        if ($this->angle != 0)
            $this->_out('Q');
        $this->angle = $angle;
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    }

    // Da chiamare ad inizio pagina
    public function filigrana($testo)
    {
        $this->SetFont('Arial', 'B', 50);
        $this->SetTextColor(203, 203, 203);
        $this->rotate(45, 55, 190);
        $this->Text(55, 190, $testo);
        $this->rotate(0);
        $this->SetTextColor(0, 0, 0);
    }

    public function box($x, $y, $hTitolo, $hTesto, $l,
                        $titolo, $testo, $borderTitolo, $borderTesto, $coloreBordoTitolo, $coloreBordoTabella,
                        $coloreTitolo, $sfondoTitolo, $coloreTabella, $sfondoTabella,
                        $sizeFontTitolo, $sizeFontTesto, $font = 'Arial')
    {

        //TITOLO
        if ($hTitolo > 0) {
            $this->SetFont($font, 'B', $sizeFontTitolo);
            $this->SetXY($x, $y);
            $this->SetTextColor($coloreTitolo[0], $coloreTitolo[1], $coloreTitolo[2]);
            $this->SetFillColor($sfondoTitolo[0], $sfondoTitolo[1], $sfondoTitolo[2]);
            $this->SetDrawColor($coloreBordoTitolo[0], $coloreBordoTitolo[1], $coloreBordoTitolo[2]);
            $this->Cell($l, $hTitolo, $titolo, $borderTitolo, 0, '', true);
        }
        //TESTO
        $this->SetXY($x, $y + 5);
        $this->SetTextColor($coloreTabella[0], $coloreTabella[1], $coloreTabella[2]);
        $this->SetFillColor($sfondoTabella[0], $sfondoTabella[1], $sfondoTabella[2]);
        $this->SetDrawColor($coloreBordoTabella[0], $coloreBordoTabella[1], $coloreBordoTabella[2]);
        $this->Cell($l, $hTesto, '', $borderTesto, 1, '', true);
        $this->SetFont($font, '', $sizeFontTesto);
        $this->SetXY($x + 1, $y + 6);
        foreach ($testo as $t) {
            $this->SetX($x + 1);
            $this->Cell($l - 4, 4, $t, 0, 1, '', true);
        }
    }


    ////////////////////////////////////////////////////
    /// Gestione Tabelle
    ////////////////////////////////////////////////////

    const TABELLA_SPAZIATURA = 5;

    protected $tabellaConfigurazioni;

    protected function valorizzaTabellaConfigurazioni()
    {
        $this->tabellaConfigurazioni = json_encode(["font" => ["family" => "Arial"],
                "coloreAlternato" => ["attivo" => true,
                    "coloreRiga" => GRIGIO_CHIARO],
                "testata" => ["attivo" => true,
                    "sfondo" => GRIGIO_SCURO,
                    "colore" => NERO,
                    "bordo" => true,
                    "bordoColore" => BIANCO,
                    "sizeRow" => 3,
                    "sizeFont" => 8],
                "corpo" => ["attivo" => true,
                    "sfondo" => BIANCO,
                    "colore" => NERO,
                    "bordo" => true,
                    "bordoColore" => BIANCO,
                    "sizeRow" => 0,
                    "sizeFont" => 8,
                    "interlinea" => 0],
                "piede" => ["attivo" => true,
                    "sfondo" => BIANCO,
                    "colore" => NERO,
                    "bordo" => true,
                    "bordoColore" => BIANCO,
                    "sizeRow" => 0,
                    "sizeFont" => 8]
            ]
        );
        $this->tabellaConfigurazioni = json_decode($this->tabellaConfigurazioni);
    }


    public function addTabellaHeaderElement($label, $colonna, $larghezza = 50, $allineamentoValore = 'L', $allineamentoTitolo = 'L')
    {
        return ['label' => $label,
            'colonna' => $colonna,
            'larghezza' => $larghezza,
            'allineamentoValore' => $allineamentoValore,
            'allineamentoTitolo' => $allineamentoTitolo];

    }


    public function addTabellaFooterElement($valore, $larghezza = 50, $allineamento = 'R')
    {
        return [
            'valore' => $valore,
            'larghezza' => $larghezza,
            'allineamento' => $allineamento
        ];
    }

    public function setTabellaConfigurazioni($key, $key2 = null, $value = null)
    {
        if (is_null($value))
            $this->tabellaConfigurazioni->$key = $key2;
        elseif (is_null($key2))
            $this->tabellaConfigurazioni = $key;
        else
            $this->tabellaConfigurazioni->$key->$key2 = $value;
    }

    public function setTabellaConfigurazioniFont($key, $value = null)
    {
        if (is_null($value))
            $this->tabellaConfigurazioni->font = $key;
        else
            $this->tabellaConfigurazioni->font->$key = $value;
    }

    public function setTabellaConfigurazioniColoreAlternato($key, $value = null)
    {
        if (is_null($value))
            $this->tabellaConfigurazioni->coloreAlternato = $key;
        else
            $this->tabellaConfigurazioni->coloreAlternato->$key = $value;
    }

    public function tabella($x, $y, $bodyObj, $tableHeaderObj, $tableFooterObj)
    {
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //TESTATA TABELLA
        $tableHeaderObj = json_decode(json_encode($tableHeaderObj));
        if ($this->tabellaConfigurazioni->testata->attivo) {

            //configurazioni grafiche
            $this->SetFont($this->tabellaConfigurazioni->font->family, 'B', $this->tabellaConfigurazioni->testata->sizeFont);
            $this->SetXY($x, $y);
            $this->setGraficaTabella('testata');
            $xApp = $x;

            //dati
            foreach ($tableHeaderObj as $headerObj) {
                $this->SetX($xApp);
                $this->Cell($headerObj->larghezza,
                    $this->tabellaConfigurazioni->testata->sizeRow + self::TABELLA_SPAZIATURA,
                    $headerObj->label,
                    $this->tabellaConfigurazioni->testata->bordo,
                    0,
                    $headerObj->allineamentoTitolo,
                    true
                );
                $xApp += $headerObj->larghezza;
            }
        }
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///CORPO TABELLA

        //configurazioni grafiche

        //Calcolo larghezza totale riga tabella
        $totRiga = 0;
        foreach ($tableHeaderObj as $r) {
            $totRiga = $totRiga + $r->larghezza;
        }

        $this->SetFont($this->tabellaConfigurazioni->font->family, '', $this->tabellaConfigurazioni->corpo->sizeFont);
        $y += $this->tabellaConfigurazioni->testata->sizeRow + $this->tabellaConfigurazioni->testata->interlinea +
            self::TABELLA_SPAZIATURA;
        $this->SetXY($x, $y);
        $this->setGraficaTabella('corpo');

        //dati

        $this->Cell($totRiga,
            count($tableHeaderObj),
            '',
            $this->tabellaConfigurazioni->corpo->bordo,
            1,
            '',
            true
        );
        $yApp = $y;

        $ckAlternato = true;
        foreach ($bodyObj as $rowBody) {

            //testo alternato

            if ($this->tabellaConfigurazioni->coloreAlternato->attivo) {
                if ($ckAlternato) {
                    $this->SetFillColor($this->tabellaConfigurazioni->corpo->sfondo[0],
                        $this->tabellaConfigurazioni->corpo->sfondo[1],
                        $this->tabellaConfigurazioni->corpo->sfondo[2]
                    );
                } else {
                    $this->SetFillColor($this->tabellaConfigurazioni->coloreAlternato->coloreRiga[0],
                        $this->tabellaConfigurazioni->coloreAlternato->coloreRiga[1],
                        $this->tabellaConfigurazioni->coloreAlternato->coloreRiga[2]
                    );
                }
                $ckAlternato = !$ckAlternato;
            }

            $xApp = $x;
            $this->SetXY($xApp, $yApp);
            foreach ($tableHeaderObj as $headerObj) {

                $content = $this->createContentAbstract($headerObj->colonna, $rowBody);

                //Creo la cella
                $this->SetX($xApp);
                $this->Cell($headerObj->larghezza,
                    $this->tabellaConfigurazioni->corpo->sizeRow +
                    $this->tabellaConfigurazioni->corpo->interlinea + self::TABELLA_SPAZIATURA,
                    $content,
                    $this->tabellaConfigurazioni->corpo->bordo,
                    0,
                    $headerObj->allineamentoValore,
                    true
                );

                $xApp += $headerObj->larghezza;

            }
            $yApp += $this->tabellaConfigurazioni->corpo->sizeRow + $this->tabellaConfigurazioni->corpo->interlinea +
                self::TABELLA_SPAZIATURA;
        }


        ////////////////////////////////////////////////////////////////////////////////////////////////////////////

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///PIE TABELLA
        $tableFooterObj = json_decode(json_encode($tableFooterObj));
        if (!is_null($tableFooterObj)) {
            $y = $this->GetY() + $this->tabellaConfigurazioni->corpo->sizeRow +
                $this->tabellaConfigurazioni->corpo->interlinea + self::TABELLA_SPAZIATURA;
            if ($this->tabellaConfigurazioni->piede->attivo) {
                //configurazioni grafiche
                $this->SetFont($this->tabellaConfigurazioni->font->family, 'B', $this->tabellaConfigurazioni->piede->sizeFont);
                $this->SetXY($x, $y);
                $this->setGraficaTabella('piede');
                $xApp = $x;


                //dati
                foreach ($tableFooterObj as $piede) {

                    $content = $this->createContentAbstract($piede->valore);

                    $this->SetX($xApp);
                    $this->Cell($piede->larghezza,
                        $this->tabellaConfigurazioni->piede->sizeRow + self::TABELLA_SPAZIATURA,
                        $content,
                        $this->tabellaConfigurazioni->piede->bordo,
                        0,
                        $piede->allineamento,
                        true
                    );
                    $xApp += $piede->larghezza;
                }

            }
        }

    }


    protected function setGraficaTabella($blocco)
    {
        $this->SetTextColor($this->tabellaConfigurazioni->$blocco->colore[0],
            $this->tabellaConfigurazioni->$blocco->colore[1],
            $this->tabellaConfigurazioni->$blocco->colore[2]
        );
        $this->SetFillColor($this->tabellaConfigurazioni->$blocco->sfondo[0],
            $this->tabellaConfigurazioni->$blocco->sfondo[1],
            $this->tabellaConfigurazioni->$blocco->sfondo[2]
        );
        if ($this->tabellaConfigurazioni->piede->bordo)
            $this->SetDrawColor($this->tabellaConfigurazioni->$blocco->bordoColore[0],
                $this->tabellaConfigurazioni->$blocco->bordoColore[1],
                $this->tabellaConfigurazioni->$blocco->bordoColore[2]
            );
    }


    public function tabellaOld(
        $x, $y, $hEtichetta, $hRiga, $hTotale, $hTotaleRighe,
        $datiEtichetta, $allineamentoEtichetta, $larghezzaEtichetta,
        $coloreEtichetta, $coloreSfondoEtichetta, $borderEtichetta, $coloreBordoEtichetta,
        $datiRiga, $allineamentoRiga, $larghezzaRiga,
        $coloreTestoRiga, $coloreSfondoRiga, $coloreRiga, $borderRiga, $coloreBordoRiga,
        $datiTotale, $allineamentoTotale, $larghezzaTotale,
        $coloreTotale, $coloreSfondoTotale, $borderTotale, $coloreBordoTotale,
        $sizeFontEtichetta, $sizeFontRiga, $sizeFontTotale,
        $interlinea, $font = 'Arial', $coloreAlternato = 'false'
    )
    {
        //Etichetta
        if ($hEtichetta > 0) {
            $this->SetFont($font, 'B', $sizeFontEtichetta);
            $this->SetXY($x, $y);
            $this->SetTextColor($coloreEtichetta[0], $coloreEtichetta[1], $coloreEtichetta[2]);
            $this->SetFillColor($coloreSfondoEtichetta[0], $coloreSfondoEtichetta[1], $coloreSfondoEtichetta[2]);
            $this->SetDrawColor($coloreBordoEtichetta[0], $coloreBordoEtichetta[1], $coloreBordoEtichetta[2]);
            $xApp = $x;
            for ($i = 0; $i < count($datiEtichetta); $i++) {
                $this->SetX($xApp);
                $this->Cell($larghezzaEtichetta[$i], $hEtichetta, $datiEtichetta[$i], $borderEtichetta, 0, $allineamentoEtichetta[$i], true);
                $xApp = $xApp + $larghezzaEtichetta[$i];
            }
        }

        //RIGHE
        $totRiga = 0;
        foreach ($larghezzaRiga as $r) {
            $totRiga = $totRiga + $r;
        }
        $this->SetFont($font, '', $sizeFontRiga);
        $y = $y + $hEtichetta + 0.2;
        $this->SetXY($x, $y);
        $this->SetTextColor($coloreTestoRiga[0], $coloreTestoRiga[1], $coloreTestoRiga[2]);
        $this->SetFillColor($coloreSfondoRiga[0], $coloreSfondoRiga[1], $coloreSfondoRiga[2]);
        $this->SetDrawColor($coloreBordoRiga[0], $coloreBordoRiga[1], $coloreBordoRiga[2]);
        $this->Cell($totRiga, $hTotaleRighe, '', $borderRiga, 1, '', true);
        $yApp = $y;
        for ($i = 0; $i < count($datiRiga); $i++) {
            $xApp = $x;
            $this->SetXY($xApp, $yApp);
            for ($j = 0; $j < count($datiRiga[$i]); $j++) {
                if ($coloreAlternato == false) {
                    $this->SetFillColor($coloreRiga[0], $coloreRiga[1], $coloreRiga[2]);
                } else {
                    if ($i % 2 == 0) {
                        $this->SetFillColor($coloreRiga[0], $coloreRiga[1], $coloreRiga[2]);
                    } else {
                        $this->SetFillColor($coloreSfondoRiga[0], $coloreSfondoRiga[1], $coloreSfondoRiga[2]);
                    }
                }
                $this->SetX($xApp);
                $this->Cell($larghezzaRiga[$j], $hRiga, $datiRiga[$i][$j], $borderRiga, 0, $allineamentoRiga[$j], true);
                $xApp = $xApp + $larghezzaRiga[$j];
            }
            $yApp = $yApp + $interlinea;
        }

        //TOTALI
        $y = $this->GetY() + $hRiga;
        if ($hTotale > 0) {
            $this->SetFont($font, 'B', $sizeFontTotale);
            $this->SetXY($x, $y);
            $this->SetTextColor($coloreTotale[0], $coloreTotale[1], $coloreTotale[2]);
            $this->SetFillColor($coloreSfondoTotale[0], $coloreSfondoTotale[1], $coloreSfondoTotale[2]);
            $this->SetDrawColor($coloreBordoTotale[0], $coloreBordoTotale[1], $coloreBordoTotale[2]);
            $xApp = $x;
            for ($i = 0; $i < count($datiTotale); $i++) {
                $this->SetX($xApp);
                $this->Cell($larghezzaTotale[$i], $hTotale, $datiTotale[$i], $borderTotale, 0, $allineamentoTotale[$i], true);
                $xApp = $xApp + $larghezzaTotale[$i];
            }
        }
    }

    /**
     * @param mixed $numeroPagina
     */
    public function setNumeroPagina($numeroPagina)
    {
        $this->numeroPagina = $numeroPagina;
    }

    /**
     * @param int $numeroPaginaTotale
     */
    public function setNumeroPaginaTotale($numeroPaginaTotale)
    {
        $this->numeroPaginaTotale = $numeroPaginaTotale;
    }

    /**
     * @param string $dataFooter
     */
    public function setDataFooter($dataFooter)
    {
        $this->dataFooter = $dataFooter;
    }

    /**
     * @param string $orientamento
     */
    public function setOrientamento($orientamento)
    {
        $this->orientamento = $orientamento;
    }

    /**
     * @param bool $stampaFooter
     */
    public function setStampaFooter($stampaFooter)
    {
        $this->stampaFooter = $stampaFooter;
    }

    /**
     * @param bool $stampaHeader
     */
    public function setStampaHeader($stampaHeader)
    {
        $this->stampaHeader = $stampaHeader;
    }


    ////////////////////////////////////////////////////////
    protected function createContentAbstract($obj, $row = null)
    {

        $content = '';
        foreach (explode('+', $obj) as $c) {


            switch (trim($c)) {
                case 'EURO':
                    $content .= ' ' . chr(128);
                    break;
                case 'PERC':
                    $content .= ' %';
                    break;
                default:
                    if (substr($c, 0, 3) == 'get') {
                        $result = $row;
                        $metodo = explode('->', trim($c));
                        foreach ($metodo as $m) {
                            $m = explode('(', $m);
                            $p = str_replace(')', '', $m[1]);
                            if ($p == '') {
                                $m = $m[0];
                                $result = $result->$m();
                            } else {
                                $p = explode(',', $p);
                                $result = call_user_func_array(array($result, $m[0]), $p);
                            }
                        }
                    } else {
                        $result = ' ' . trim($c);
                    }

                    $content .= $result;
            }
        }
        return $content;
    }


}