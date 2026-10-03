<?php

use PHPUnit\Framework\TestCase;

class DummyLogger
{
    public function log(string $pesan): void
    {
        throw new \Exception("DummyLogger tidak boleh dipanggil! Method log() terpanggil dengan pesan: $pesan");
    }

    public function simpan(array $data): void
    {
        throw new \Exception("DummyLogger tidak boleh dipanggil! Method simpan() terpanggil.");
    }
}

class KalkulasiHarga
{
    private $logger;

    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    public function hitungHargaSetelahDiskon(int $hargaAsli, int $diskonPersen): int
    {
        return $hargaAsli - ($hargaAsli * ($diskonPersen / 100));
    }

    public function hitungSubtotal(int $hargaFinal, int $jumlah): int
    {
        return $hargaFinal * $jumlah;
    }
}

class HargaObatTest extends TestCase
{
    private DummyLogger $dummyLogger;
    private KalkulasiHarga $kalkulasi;

    protected function setUp(): void
    {
        $this->dummyLogger = new DummyLogger();
        $this->kalkulasi = new KalkulasiHarga($this->dummyLogger);
    }

    /** @test */
    public function test_harga_tanpa_diskon_tetap_sama()
    {
        $hasil = $this->kalkulasi->hitungHargaSetelahDiskon(10000, 0);
        $this->assertEquals(10000, $hasil);
    }

    /** @test */
    public function test_harga_dengan_diskon_20_persen()
    {
        $hasil = $this->kalkulasi->hitungHargaSetelahDiskon(10000, 20);
        $this->assertEquals(8000, $hasil);
    }

    /** @test */
    public function test_harga_dengan_diskon_50_persen()
    {
        $hasil = $this->kalkulasi->hitungHargaSetelahDiskon(20000, 50);
        $this->assertEquals(10000, $hasil);
    }

    /** @test */
    public function test_subtotal_dua_item_obat()
    {
        $hasil = $this->kalkulasi->hitungSubtotal(8000, 3);
        $this->assertEquals(24000, $hasil);
    }

    /** @test */
    public function test_subtotal_satu_item_obat()
    {
        $hasil = $this->kalkulasi->hitungSubtotal(15000, 1);
        $this->assertEquals(15000, $hasil);
    }

    /** @test */
    public function test_dummy_tidak_dipanggil_saat_hitung_harga()
    {
        try {
            $this->kalkulasi->hitungHargaSetelahDiskon(5000, 10);
            $this->assertTrue(true);
        } catch (\Exception $e) {
            $this->fail("DummyLogger tidak seharusnya dipanggil: " . $e->getMessage());
        }
    }
}