<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Foret;

class ForetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Due to the large dataset, we'll process forests in chunks
        $this->createForestsBatch1();
        $this->createForestsBatch2();
        $this->createForestsBatch3();
        $this->createForestsBatch4();
        $this->createForestsBatch5();
        $this->createForestsBatch6();
        $this->createForestsBatch7();
        $this->createForestsBatch8();
        $this->createForestsBatch9();
        $this->createForestsBatch10();
    }

    private function createForestsBatch1()
    {
        $forets = [
            ['foret' => 'Saka', 'lat' => '34.62318037', 'log' => '-3.341291647'],
            ['foret' => 'Mezguitem', 'lat' => '34.38750905', 'log' => '-3.649986836'],
            ['foret' => 'El Kifane', 'lat' => '34.57840137', 'log' => '-3.77415522'],
            ['foret' => 'Ain Aokka', 'lat' => '34.60013377', 'log' => '-3.908468354'],
            ['foret' => 'Rmel', 'lat' => '34.80967857', 'log' => '-5.719325039'],
            ['foret' => 'Moyen Ouergha', 'lat' => '34.67752648', 'log' => '-4.854234223'],
            ['foret' => 'Jbel Amzez', 'lat' => '34.84392652', 'log' => '-5.307906011'],
            ['foret' => 'Izarene', 'lat' => '34.82418629', 'log' => '-5.470127315'],
            ['foret' => 'Tazagrart', 'lat' => '35.0901389', 'log' => '-2.255387556'],
            ['foret' => 'Beni Snassen', 'lat' => '34.7826565', 'log' => '-2.55689942'],
            ['foret' => 'Ain Karma', 'lat' => '34.4841299', 'log' => '-1.855361908'],
            ['foret' => 'Amgala', 'lat' => '0', 'log' => '0'],
            ['foret' => 'Aousserd', 'lat' => '0', 'log' => '0'],
            ['foret' => 'Bir Anzerane', 'lat' => '0', 'log' => '0'],
            ['foret' => 'Zekkara', 'lat' => '34.47815937', 'log' => '-2.177770635'],
            ['foret' => 'Beni Yaala', 'lat' => '34.3527782', 'log' => '-2.195682232'],
            ['foret' => 'El Ayate', 'lat' => '34.31396974', 'log' => '-2.589737353'],
            ['foret' => 'Debdou', 'lat' => '33.98559047', 'log' => '-2.909160813'],
            ['foret' => 'Chiker', 'lat' => '34.07904419', 'log' => '-4.025220991'],
            ['foret' => 'Bab Azhar', 'lat' => '34.04986876', 'log' => '-4.236395451'],
        ];

        foreach ($forets as $foret) {
            Foret::create($foret);
        }
    }

    private function createForestsBatch2()
    {
        $forets = [
            ['foret' => 'Ras El Ksar', 'lat' => '33.90538098', 'log' => '-3.909908625'],
            ['foret' => 'Meghraoua', 'lat' => '33.87064834', 'log' => '-4.123861693'],
            ['foret' => 'Berkine', 'lat' => '33.78034347', 'log' => '-3.750138471'],
            ['foret' => 'Tamjilt', 'lat' => '33.64558082', 'log' => '-4.023831683'],
            ['foret' => 'Taffert', 'lat' => '33.69976374', 'log' => '-4.237784758'],
            ['foret' => 'Tizi Ntlaghmine', 'lat' => '33.77478625', 'log' => '-4.355875732'],
            ['foret' => 'Ghomra', 'lat' => '0', 'log' => '0'],
            ['foret' => 'Tainest', 'lat' => '0', 'log' => '0'],
            ['foret' => 'Haut Sebou', 'lat' => '33.74700014', 'log' => '-4.610257595'],
            ['foret' => 'El Adrej', 'lat' => '33.6164054', 'log' => '-4.362961193'],
            ['foret' => 'Ait Bouhou', 'lat' => '33.63446637', 'log' => '-4.73335008'],
            ['foret' => 'Sefrou', 'lat' => '33.59556582', 'log' => '-4.907013282'],
            ['foret' => 'Jbel Aoua Nord', 'lat' => '33.65391666', 'log' => '-5.043165235'],
            ['foret' => 'Immouzer marmoucha', 'lat' => '33.43301706', 'log' => '-4.324894223'],
            ['foret' => 'Guigou', 'lat' => '33.52054332', 'log' => '-4.620816323'],
            ['foret' => 'Jaaba', 'lat' => '33.55805457', 'log' => '-5.2307215'],
            ['foret' => 'DUNES LAAOUAMRA', 'lat' => '0', 'log' => '0'],
            ['foret' => 'DUNES MERZOUGA', 'lat' => '0', 'log' => '0'],
            ['foret' => 'Ait Bourzouine', 'lat' => '33.58028346', 'log' => '-5.538452704'],
            ['foret' => 'Ain Nokra', 'lat' => '33.41217748', 'log' => '-4.734739388'],
        ];

        foreach ($forets as $foret) {
            Foret::create($foret);
        }
    }

    private function createForestsBatch3()
    {
        $forets = [
            ['foret' => 'Azrou', 'lat' => '33.43440636', 'log' => '-5.066783437'],
            ['foret' => 'Sidi Mguild', 'lat' => '33.20742856', 'log' => '-5.315469147'],
            ['foret' => 'Ain Leuh', 'lat' => '33.30166978', 'log' => '-5.412025883'],
            ['foret' => 'Mkhenza', 'lat' => '0', 'log' => '0'],
            ['foret' => 'Bekrit', 'lat' => '33.09265647', 'log' => '-5.165346947'],
            ['foret' => 'Aghbalou Larbi', 'lat' => '33.18404191', 'log' => '-4.903540017'],
            ['foret' => 'Senoual', 'lat' => '32.86295794', 'log' => '-5.276491405'],
            ['foret' => 'Itzer', 'lat' => '32.97410239', 'log' => '-5.098660279'],
            ['foret' => 'Kerrouchen', 'lat' => '32.80209864', 'log' => '-5.333477384'],
            ['foret' => 'Talsint', 'lat' => '32.61315306', 'log' => '-3.822607497'],
            ['foret' => 'Aferket', 'lat' => '32.57425251', 'log' => '-4.05045362'],
            ['foret' => 'Midelt', 'lat' => '32.58953487', 'log' => '-4.777755128'],
            ['foret' => 'Zaouit Sidi Hamza', 'lat' => '32.46727597', 'log' => '-4.687450265'],
            ['foret' => 'Ayachi', 'lat' => '32.56313807', 'log' => '-5.009769175'],
            ['foret' => 'Tounfite', 'lat' => '32.48255834', 'log' => '-5.159814182'],
            ['foret' => 'Sidi Yahia O Youssef', 'lat' => '32.45743565', 'log' => '-5.310409864'],
            ['foret' => 'Bou Adil', 'lat' => '32.45477222', 'log' => '-5.497971175'],
            ['foret' => 'Tirghiste', 'lat' => '32.31862027', 'log' => '-5.386826725'],
            ['foret' => 'Agoudim', 'lat' => '32.4005893', 'log' => '-5.208995599'],
            ['foret' => 'Michlefene', 'lat' => '32.31987064', 'log' => '-5.170095042'],
        ];

        foreach ($forets as $foret) {
            Foret::create($foret);
        }
    }

    private function createForestsBatch4()
    {
        $forets = [
            ['foret' => 'Foum Teguet', 'lat' => '33.16178987', 'log' => '-5.552154096'],
            ['foret' => 'Khenifra', 'lat' => '33.04925611', 'log' => '-5.661909238'],
            ['foret' => 'Aghbal', 'lat' => '33.05620264', 'log' => '-5.496581867'],
            ['foret' => 'Ajdir', 'lat' => '32.97840152', 'log' => '-5.340979636'],
            ['foret' => '00_Khenifra', 'lat' => '32.81683779', 'log' => '-5.692982896'],
            ['foret' => 'Ait Issaq Nord', 'lat' => '32.80890623', 'log' => '-5.805285581'],
            ['foret' => 'Ait Issaq Nord', 'lat' => '32.71026553', 'log' => '-5.677469461'],
            ['foret' => '00_Kebbab', 'lat' => '32.72415858', 'log' => '-5.489913203'],
            ['foret' => 'Aghbala', 'lat' => '32.31848134', 'log' => '-5.710812795'],
            ['foret' => 'Ait Oum El Bakht', 'lat' => '32.58383872', 'log' => '-5.802506973'],
            ['foret' => 'Ait Ouirra', 'lat' => '32.52548788', 'log' => '-5.936019248'],
            ['foret' => 'Ait Mohannd', 'lat' => '32.46852635', 'log' => '-6.056888837'],
            ['foret' => 'Ait Abdellouli', 'lat' => '32.4073969', 'log' => '-6.112461065'],
            ['foret' => 'Ait Said ou ALi', 'lat' => '32.31889813', 'log' => '-6.192346131'],
            ['foret' => 'Ait Atta Noumalou', 'lat' => '32.24665423', 'log' => '-6.353505594'],
            ['foret' => 'Ait Bouzid', 'lat' => '32.15634937', 'log' => '-6.482711014'],
            ['foret' => 'Ait Mazigh', 'lat' => '32.02714394', 'log' => '-6.329887391'],
            ['foret' => 'Ait Isha Nord', 'lat' => '32.1382884', 'log' => '-6.260422115'],
            ['foret' => 'Ait Isha Sud', 'lat' => '32.05354075', 'log' => '-6.135384602'],
            ['foret' => 'Ait Daoud Ou Ali', 'lat' => '32.2619366', 'log' => '-5.958942785'],
        ];

        foreach ($forets as $foret) {
            Foret::create($foret);
        }
    }

    private function createForestsBatch5()
    {
        $forets = [
            ['foret' => 'Anergui', 'lat' => '31.45315772', 'log' => '-7.22164589'],
            ['foret' => 'EL HMADA', 'lat' => '0', 'log' => '0'],
            ['foret' => 'Adrar Stouf', 'lat' => '0', 'log' => '0'],
            ['foret' => 'GUELTAT ZEMMOUR', 'lat' => '0', 'log' => '0'],
            ['foret' => 'Deroua', 'lat' => '32.31473021', 'log' => '-6.415857509'],
            ['foret' => 'Ighil Noumarad', 'lat' => '32.04409539', 'log' => '-6.637501762'],
            ['foret' => 'Azilal', 'lat' => '32.03715064', 'log' => '-6.476036345'],
            ['foret' => 'Ait Abbes', 'lat' => '31.77845873', 'log' => '-6.587152332'],
            ['foret' => 'Ait Mhamed', 'lat' => '31.86353191', 'log' => '-6.35450323'],
            ['foret' => 'Ouaoulla', 'lat' => '31.87568522', 'log' => '-6.706949253'],
            ['foret' => 'Entifa', 'lat' => '32.00763546', 'log' => '-6.917027928'],
            ['foret' => 'Zaouia Ahancal', 'lat' => '31.88089378', 'log' => '-5.984868947'],
            ['foret' => 'Ait Bouguemez', 'lat' => '31.73428423', 'log' => '-6.488222388'],
            ['foret' => '00_Lakhdar', 'lat' => '31.76630542', 'log' => '-6.828482368'],
            ['foret' => 'Azilal', 'lat' => '31.76804161', 'log' => '-7.000364913'],
            ['foret' => 'Ouarkziz', 'lat' => '0', 'log' => '0'],
            ['foret' => 'Tizimi', 'lat' => '0', 'log' => '0'],
            ['foret' => 'Rzef', 'lat' => '31.66734274', 'log' => '-6.823273808'],
            ['foret' => 'Massif de l\'Iskt', 'lat' => '31.61004856', 'log' => '-7.062867658'],
            ['foret' => 'Basse Tassaout', 'lat' => '31.68470461', 'log' => '-7.162003961'],
        ];

        foreach ($forets as $foret) {
            Foret::create($foret);
        }
    }

    private function createForestsBatch6()
    {
        $forets = [
            ['foret' => '00_Lakhdar', 'lat' => '31.59095049', 'log' => '-6.568227886'],
            ['foret' => 'Rhmet', 'lat' => '31.57879719', 'log' => '-6.698441935'],
            ['foret' => 'Ait Tamlil', 'lat' => '31.44858313', 'log' => '-6.891158736'],
            ['foret' => 'Rhejdama', 'lat' => '31.51629444', 'log' => '-7.219298138'],
            ['foret' => 'Glaoua Nord', 'lat' => '31.50358053', 'log' => '-7.334604408'],
            ['foret' => 'Ait Affan', 'lat' => '31.49198782', 'log' => '-6.570137696'],
            ['foret' => 'Touggana', 'lat' => '31.46478574', 'log' => '-7.416111091'],
            ['foret' => 'Mesfioua', 'lat' => '31.28017297', 'log' => '-7.555076772'],
            ['foret' => 'Glaoua Sud', 'lat' => '31.39546331', 'log' => '-7.300613593'],
            ['foret' => 'Ourika', 'lat' => '31.2331039', 'log' => '-7.770711239'],
            ['foret' => 'Sektana', 'lat' => '31.14210411', 'log' => '-7.920700792'],
            ['foret' => 'Ouzguita', 'lat' => '31.17166126', 'log' => '-8.064126892'],
            ['foret' => 'Guedmioua', 'lat' => '31.15730881', 'log' => '-8.283545648'],
            ['foret' => 'Mzouda', 'lat' => '31.201545', 'log' => '-8.537658656'],
            ['foret' => 'Deuirane', 'lat' => '31.16357879', 'log' => '-8.697420743'],
            ['foret' => 'Seksaoua', 'lat' => '31.05916363', 'log' => '-8.722306973'],
            ['foret' => 'Goundafa', 'lat' => '31.0762065', 'log' => '-8.003999717'],
            ['foret' => 'Ida Ou Mahmoud', 'lat' => '30.77407436', 'log' => '-8.90096919'],
            ['foret' => 'Abda', 'lat' => '32.08469987', 'log' => '-9.250906428'],
            ['foret' => 'Arhana', 'lat' => '32.0534485', 'log' => '-9.313409172'],
        ];

        foreach ($forets as $foret) {
            Foret::create($foret);
        }
    }

    private function createForestsBatch7()
    {
        $forets = [
            ['foret' => 'Essaouira Nord', 'lat' => '31.53780086', 'log' => '-9.657174269'],
            ['foret' => 'Essaouira Sud', 'lat' => '31.39369731', 'log' => '-9.764817883'],
            ['foret' => 'Argane hanchane', 'lat' => '31.60254772', 'log' => '-9.426624016'],
            ['foret' => 'Argane El Baz', 'lat' => '31.6280245', 'log' => '-9.473348796'],
            ['foret' => 'Jbel Lahdid Nord', 'lat' => '31.71087531', 'log' => '-9.518241212'],
            ['foret' => 'Talat ouargane', 'lat' => '31.67322347', 'log' => '-9.636340021'],
            ['foret' => 'Kudiat Mrirt', 'lat' => '31.65255853', 'log' => '-9.570282748'],
            ['foret' => 'Ain Lahjar', 'lat' => '31.61072073', 'log' => '-9.606824832'],
            ['foret' => 'Tiguimijjou', 'lat' => '31.62359236', 'log' => '-9.572451733'],
            ['foret' => 'Sidi Moussa', 'lat' => '31.55905588', 'log' => '-9.496199745'],
            ['foret' => 'Ayed', 'lat' => '31.5425877', 'log' => '-9.511488144'],
            ['foret' => '00_Arbayâ', 'lat' => '31.49265999', 'log' => '-9.47313841'],
            ['foret' => 'Aouintiri', 'lat' => '31.54138445', 'log' => '-9.374918097'],
            ['foret' => 'Ain Tafetacht', 'lat' => '31.47854225', 'log' => '-9.361998024'],
            ['foret' => '00_Ain Tafetacht', 'lat' => '31.5464818', 'log' => '-9.214446499'],
            ['foret' => 'Adamna', 'lat' => '31.48593227', 'log' => '-9.620311564'],
            ['foret' => 'Ait Sraidi', 'lat' => '31.47086422', 'log' => '-9.655231633'],
            ['foret' => 'Bouzamma', 'lat' => '31.44686805', 'log' => '-9.672397181'],
            ['foret' => 'Ait Tahala', 'lat' => '31.46379588', 'log' => '-9.629426549'],
            ['foret' => 'Ounagha', 'lat' => '31.49728674', 'log' => '-9.59065983'],
        ];

        foreach ($forets as $foret) {
            Foret::create($foret);
        }
    }

    private function createForestsBatch8()
    {
        $forets = [
            ['foret' => 'Tissakatine', 'lat' => '31.45382857', 'log' => '-9.581319974'],
            ['foret' => 'Guettote', 'lat' => '31.45728517', 'log' => '-9.525255308'],
            ['foret' => 'Ain Auodir', 'lat' => '31.39217815', 'log' => '-9.477076108'],
            ['foret' => 'Ouled Amira', 'lat' => '31.46920405', 'log' => '-9.459198035'],
            ['foret' => 'Rhezaoua', 'lat' => '31.33097755', 'log' => '-9.737504205'],
            ['foret' => '00_Iberjaguane', 'lat' => '31.38957387', 'log' => '-9.693231429'],
            ['foret' => 'Neknafa', 'lat' => '31.29451761', 'log' => '-9.628124405'],
            ['foret' => 'Idmine', 'lat' => '31.32837327', 'log' => '-9.694533573'],
            ['foret' => 'Tidzi', 'lat' => '31.28670477', 'log' => '-9.772661998'],
            ['foret' => 'Amerdma', 'lat' => '31.23709322', 'log' => '-9.686720725'],
            ['foret' => 'Sidi Slimane', 'lat' => '31.30477547', 'log' => '-9.534830885'],
            ['foret' => 'Sidi Ghanem', 'lat' => '31.33005312', 'log' => '-9.413539715'],
            ['foret' => 'Tamsrirt', 'lat' => '31.37358229', 'log' => '-9.35033318'],
            ['foret' => 'Ida Ouissaren', 'lat' => '31.16677762', 'log' => '-9.784381269'],
            ['foret' => 'Amsitten', 'lat' => '31.16156906', 'log' => '-9.642447949'],
            ['foret' => 'Isk Nzbib', 'lat' => '31.20724057', 'log' => '-9.503618446'],
            ['foret' => 'Tamaroute', 'lat' => '31.24490606', 'log' => '-9.473169684'],
            ['foret' => 'Issig nakoucht', 'lat' => '31.19517495', 'log' => '-9.447806245'],
            ['foret' => 'Jabal Ihchach', 'lat' => '31.198029', 'log' => '-9.3676963'],
            ['foret' => '00_Tasserssirt', 'lat' => '31.04958498', 'log' => '-9.780474845'],
        ];

        foreach ($forets as $foret) {
            Foret::create($foret);
        }
    }

    private function createForestsBatch9()
    {
        $forets = [
            ['foret' => 'Imgrad', 'lat' => '31.10167061', 'log' => '-9.651562933'],
            ['foret' => 'Maraou', 'lat' => '31.13552626', 'log' => '-9.529161724'],
            ['foret' => 'Tihamrine', 'lat' => '31.13943267', 'log' => '-9.45884614'],
            ['foret' => 'Kouzemt', 'lat' => '31.0886492', 'log' => '-9.405458379'],
            ['foret' => 'Tamanar Nord', 'lat' => '31.07899469', 'log' => '-9.620744678'],
            ['foret' => 'Isk Iguenouane', 'lat' => '31.06130425', 'log' => '-9.641145813'],
            ['foret' => 'Jbel Imouzgaoune', 'lat' => '31.08734706', 'log' => '-9.553902396'],
            ['foret' => 'Iffers', 'lat' => '31.05088713', 'log' => '-9.475773964'],
            ['foret' => 'Tamanar Sud', 'lat' => '30.93499662', 'log' => '-9.779172702'],
            ['foret' => 'Ida Ou Guelloul', 'lat' => '30.88030672', 'log' => '-9.676303605'],
            ['foret' => 'Issik Oumagour', 'lat' => '31.01561733', 'log' => '-9.310040773'],
            ['foret' => 'Mtouga Sud', 'lat' => '30.97220856', 'log' => '-9.108418092'],
            ['foret' => 'Aghoundfar', 'lat' => '31.0469807', 'log' => '-9.268733627'],
            ['foret' => 'Aghzifen', 'lat' => '31.01833361', 'log' => '-9.258316499'],
            ['foret' => 'Aguerd Douchouelt', 'lat' => '30.97796725', 'log' => '-9.284359315'],
            ['foret' => 'Lalla Ijja', 'lat' => '30.95427584', 'log' => '-9.216192343'],
            ['foret' => 'Amchtoutel', 'lat' => '30.92327735', 'log' => '-9.232273691'],
            ['foret' => 'Anzougarn', 'lat' => '30.88450816', 'log' => '-9.297781534'],
            ['foret' => 'Tamkadoute', 'lat' => '30.86036467', 'log' => '-9.302225841'],
            ['foret' => 'Ait Aissi', 'lat' => '30.93836848', 'log' => '-9.504609387'],
        ];

        foreach ($forets as $foret) {
            Foret::create($foret);
        }
    }

    private function createForestsBatch10()
    {
        $forets = [
            ['foret' => 'Tigouine', 'lat' => '30.83012344', 'log' => '-9.281411185'],
            ['foret' => 'Dunes Arouaiss', 'lat' => '30.80726477', 'log' => '-9.534333664'],
            ['foret' => 'Idi Ouddar', 'lat' => '30.7935923', 'log' => '-9.796796361'],
            ['foret' => 'Talmest', 'lat' => '30.78480285', 'log' => '-9.782147277'],
            ['foret' => 'Lemgo', 'lat' => '31.13143406', 'log' => '-8.97109665'],
            ['foret' => 'Mtouga Nord', 'lat' => '31.13432748', 'log' => '-9.300451647'],
            ['foret' => 'Taskamt', 'lat' => '30.9304504', 'log' => '-9.070398201'],
            ['foret' => 'Dunes de Tamri', 'lat' => '30.73492273', 'log' => '-9.852536109'],
            ['foret' => 'Ain Tamaloukte', 'lat' => '30.66826941', 'log' => '-9.848873842'],
            ['foret' => 'Ait Khemis', 'lat' => '30.67925622', 'log' => '-9.714102305'],
            ['foret' => 'Aoujdad', 'lat' => '30.75982617', 'log' => '-9.613756098'],
            ['foret' => 'Tinkert', 'lat' => '30.75982617', 'log' => '-9.563216767'],
            ['foret' => 'Tadarte Imzilene', 'lat' => '30.64702824', 'log' => '-9.58738775'],
            ['foret' => 'Taska Oudrar', 'lat' => '30.79644887', 'log' => '-9.471367035'],
            ['foret' => 'Taznakhte', 'lat' => '30.55754678', 'log' => '-9.672181517'],
            ['foret' => 'Immouzzer', 'lat' => '30.5985642', 'log' => '-9.547176028'],
            ['foret' => 'Timristine', 'lat' => '30.70892061', 'log' => '-9.468070992'],
            ['foret' => 'Ain Asmama', 'lat' => '30.75189125', 'log' => '-9.328416427'],
            ['foret' => 'Ifesfassene', 'lat' => '30.80755776', 'log' => '-9.276656342'],
            ['foret' => 'Tizgui Nchourfa', 'lat' => '30.59465779', 'log' => '-9.445609068'],
        ];

        foreach ($forets as $foret) {
            Foret::firstOrCreate(
                ['foret' => $foret['foret']],
                $foret
            );
        }
    }

    /**
     * Load forets from JSON file
     */
    private function loadFromJson(string $jsonPath): void
    {
        $json = file_get_contents($jsonPath);
        $data = json_decode($json, true) ?? [];

        if (empty($data)) {
            $this->command->warn('Foret.json file is empty or invalid JSON');
            return;
        }

        $this->command->info('Loading ' . count($data) . ' forets from Foret.json');

        foreach ($data as $item) {
            $foretName = $item['foret'] ?? null;
            if (!$foretName) {
                continue;
            }

            Foret::firstOrCreate(
                ['foret' => $foretName],
                [
                    'lat' => $item['lat'] ?? '0',
                    'log' => $item['log'] ?? '0',
                    'is_deleted' => false,
                ]
            );
        }

        $this->command->info('Forets seeded successfully!');
    }
}
