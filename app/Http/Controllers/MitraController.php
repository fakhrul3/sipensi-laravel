<?php

namespace App\Http\Controllers;

class MitraController extends Controller
{
    public function index()
    {
        // ====== LIST FILE (sesuai zip) ======
        $tabs = [
            'investasi_keuangan' => [
                'label' => 'Investasi & Keuangan',
                'files' => [
                    'bank_bri.png',
                    'BNI VENTURE.png',
                    'bizhare.png',
                    'east_ventures.jpg',
                    'efunding.png',
                    'FUNDEX.png',
                    'IIX Logo.png',
                    'INVST(1).png',
                    'KEJORA.png',
                    'lbs_urundana.png',
                    'mandiri_capital.jpg',
                    'pegadaian.png',
                    'pnm.png',
                    'SWEEF CAPITAL.png',
                    'winpay.png',
                    'Logo_ALAMI_Colour.png',
                ],
            ],
            'pemerintah_bumn' => [
                'label' => 'Instansi Pemerintah & BUMN',
                'files' => [
                    'BRIN.png',
                    'Badan Informasi Geospasial.png',
                    'geospasial.png',
                    'balai_diklat_denpasar.png',
                    'dinkop_diy.png',
                    'dinkop_ntt.png',
                    'dinkop_sumbar.jpg',
                    'diskuk_jabar.png',
                    'DIY.png',
                    'djki_kumham.png',
                    'komdigi.png',
                    'KEMENBUD.png',
                    'KEMENPERIN.png',
                    'pemprov_bali.png',
                    'PERUM BULOG.png',
                    'PLN INDONESIA POWER.png',
                    'PT PLN.png',
                    'PT POS.png',
                    'smescco.png',
                    'cannadian_embassy.png',
                ],
            ],
            'universitas' => [
                'label' => 'Universitas',
                'files' => [
                    'binus_univ.png',
                    'UNIVERSITAS CIPUTRA.png',
                    'ciputra entrepreneurs.png',
                    'ibisma_uii.png',
                    'inovasi_ugm.png',
                    'podomoro_university.png',
                    'stikom_bali.png',
                    'stp_univ_hassanudin.png',
                    'dikst unbraw.png',
                    'inbis_stikom.png',
                    'inbistek_andalas.png',
                    'lppm.png',
                    'univ_jendral_soedirman.png',
                    'univ_sumatera_utara.png',
                    'univ_udayana.png',
                    'tsinghua.png',
                    'unikl_logo.jpg',
                    'wadhawani.png',
                ],
            ],
            'komunitas_sosial' => [
                'label' => 'Komunitas Sosial',
                'files' => [
                    'DOMPET DHUAFA.png',
                    'du_anyam.png',
                    'eka_tjipta_foundation.png',
                    'RAHMANIA FOUNDATION.png',
                    'RUMAHZAKAT.png',
                    'the_local_enablers.png',
                    'YAYASAN GUDANG HIKMAT 1.png',
                    'YAYASAN GUDANG HIKMAT 2.png',
                    'ydba.png',
                    'wesolve.png',
                    'Amati-Indonesia.png',
                    'candra_naya_lestari.png',
                ],
            ],
            'industri_korporasi' => [
                'label' => 'Industri & Korporasi',
                'files' => [
                    'agrindo.png',
                    'akar_kita.png',
                    'apindo.png',
                    'apotek_roxy.png',
                    'biosirkular.jpg',
                    'HIPPINDO.png',
                    'perhimpunan_hotel_dan_restoran_indonesia.png',
                    'paragon_corp.png',
                    'SARINAH.png',
                    'SARIRAYA.png',
                    'ALFAMART.png',
                    'INDOMARET.png',
                    'PT AWINA.png',
                    'PT BIMASAKTI.png',
                    'PT EVERSHINE.png',
                    'PT INOVA.png',
                    'pt_braja_biru_abadi.png',
                    'pt_ever_shine.png',
                    'impala.png',
                    'katamistry.png',
                    'kekean.jpg',
                    'lewis_organic.png',
                    'panda_lovely.png',
                    'brilliant.png',
                ],
            ],
            'teknologi_digital' => [
                'label' => 'Teknologi & Digital',
                'files' => [
                    'Huawei.png',
                    'Logo Fortius Solusi Informatika.png',
                    'kode_creative_hub.png',
                    'SEEDBACKLINK.png',
                    'block71.png',
                    'instellar.png',
                    'SYNNOVAC.png',
                    'tbf.png',
                    'temu.png',
                    'GRAB.png',
                    'SHOPEE.png',
                    'lazada.png',
                    'lalamove.png',
                    'LIONPARCEL.png',
                    'nexmedis.png',
                    'dibimbing.png',
                    'maxi academy.png',
                    'my_school_indonesia.jpg',
                    'asia_coach.png',
                    'TOP LEGAL.png',
                    'ANGINADVISORY.png',
                    'SETC.png',
                    'idb_bali.png',
                ],
            ],
        ];

        // ====== AUTO-DETECT BASE FOLDER LOGO ======
        // Kamu bilang pindah ke: public/img/mitra/gambar
        // Tapi sering kejadian file masih bersarang: public/img/mitra/gambar/mitra/Business Matching SAP/
        $candidates = [
            // yang kamu inginkan
            ['disk' => public_path('img/mitra/'), 'url' => 'img/mitra/'],

           
        ];

        $mitraBaseUrl = 'img/mitra/'; // default
        foreach ($candidates as $c) {
            if (is_dir($c['disk'])) {
                // cek minimal satu file ada
                $probe = $tabs['investasi_keuangan']['files'][0] ?? null;
                if ($probe && file_exists($c['disk'] . DIRECTORY_SEPARATOR . $probe)) {
                    $mitraBaseUrl = $c['url'];
                    break;
                }
            }
        }

        return view('mitra.index', compact('tabs', 'mitraBaseUrl'));
    }
}
