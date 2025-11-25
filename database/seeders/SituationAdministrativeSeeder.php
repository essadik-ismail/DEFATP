<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SituationAdministrative;

class SituationAdministrativeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks temporarily
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Delete all existing records
        SituationAdministrative::query()->delete();
        
        // Re-enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $situations = [
            // Tétouan
            ['commune' => 'Souk Lakdim', 'province' => 'Tétouan'],
            ['commune' => 'Ain Lahcen', 'province' => 'Tétouan'],
            ['commune' => 'Ben Karrich', 'province' => 'Tétouan'],
            ['commune' => 'Beghaghza', 'province' => 'Tétouan'],
            ['commune' => 'El Kharroub', 'province' => 'Tétouan'],
            ['commune' => 'El Hamra', 'province' => 'Tétouan'],
            ['commune' => 'Béni Lait', 'province' => 'Tétouan'],
            ['commune' => 'El Oued', 'province' => 'Tétouan'],
            ['commune' => 'Sahtriyine', 'province' => 'Tétouan'],
            ['commune' => 'Zaouiat Sidi Kacem', 'province' => 'Tétouan'],
            ['commune' => 'Azla', 'province' => 'Tétouan'],
            ['commune' => 'Béni Harchane', 'province' => 'Tétouan'],
            ['commune' => 'Zinate', 'province' => 'Tétouan'],
            ['commune' => 'Zaitoune', 'province' => 'Tétouan'],
            ['commune' => 'Mallaliyine', 'province' => 'Tétouan'],
            
            // Taza
            ['commune' => 'Smiaâ', 'province' => 'Taza'],
            ['commune' => 'Matmata', 'province' => 'Taza'],
            ['commune' => 'Maghraoua', 'province' => 'Taza'],
            ['commune' => 'Bouiblane', 'province' => 'Taza'],
            ['commune' => 'Oulad Zbair', 'province' => 'Taza'],
            ['commune' => 'Bab Marzouka', 'province' => 'Taza'],
            ['commune' => 'Bab Boudir', 'province' => 'Taza'],
            ['commune' => 'Traiba', 'province' => 'Taza'],
            ['commune' => 'Beni Fteh', 'province' => 'Taza'],
            ['commune' => 'Taineste', 'province' => 'Taza'],
            ['commune' => 'Bouhlou', 'province' => 'Taza'],
            ['commune' => 'Boured', 'province' => 'Taza'],
            ['commune' => 'Bouchfaâ', 'province' => 'Taza'],
            ['commune' => 'El Gouzate', 'province' => 'Taza'],
            ['commune' => 'Kaf El Ghar', 'province' => 'Taza'],
            ['commune' => 'Msila', 'province' => 'Taza'],
            ['commune' => 'Brarha', 'province' => 'Taza'],
            ['commune' => 'Taifa', 'province' => 'Taza'],
            
            // Taourirt
            ['commune' => 'Debdou', 'province' => 'Taourirt'],
            ['commune' => 'El Aioun', 'province' => 'Taourirt'],
            ['commune' => 'Sidi Ali Belkacem', 'province' => 'Taourirt'],
            
            // Taounate
            ['commune' => 'Ain Mediouna', 'province' => 'Taounate'],
            ['commune' => 'Bouaadel', 'province' => 'Taounate'],
            ['commune' => 'Tamedite', 'province' => 'Taounate'],
            ['commune' => 'Tafrante', 'province' => 'Taounate'],
            ['commune' => 'Ourtzagh', 'province' => 'Taounate'],
            ['commune' => 'Tabouda', 'province' => 'Taounate'],
            ['commune' => 'Fannassa Bab El Heit', 'province' => 'Taounate'],
            
            // Tanger-Assila
            ['commune' => 'Dar Chaoui', 'province' => 'Tanger-Assila'],
            ['commune' => 'El Menzla', 'province' => 'Tanger-Assila'],
            ['commune' => 'Hjar Nhal', 'province' => 'Tanger-Assila'],
            ['commune' => 'Gueznaya', 'province' => 'Tanger-Assila'],
            
            // Skhirat Témara
            ['commune' => 'Sabbah', 'province' => 'Skhirat Témara'],
            ['commune' => 'Elmenzeh', 'province' => 'Skhirat Témara'],
            ['commune' => 'Sidi Yahya Zair', 'province' => 'Skhirat Témara'],
            
            // Sidi Slimane
            ['commune' => 'Kceibia', 'province' => 'Sidi Slimane'],
            ['commune' => 'Dar Belamri', 'province' => 'Sidi Slimane'],
            
            // Sidi Kacem
            ['commune' => 'Ain Dfali', 'province' => 'Sidi Kacem'],
            ['commune' => 'Lamrabih', 'province' => 'Sidi Kacem'],
            ['commune' => 'Moulay Abdelkader', 'province' => 'Sidi Kacem'],
            
            // Settat
            ['commune' => 'Mzamza Janoubiya', 'province' => 'Settat'],
            ['commune' => 'M\'zoura', 'province' => 'Settat'],
            ['commune' => 'Beni Khloug', 'province' => 'Settat'],
            ['commune' => 'Settat', 'province' => 'Settat'],
            ['commune' => 'Ouled Faress', 'province' => 'Settat'],
            ['commune' => 'Mgartou', 'province' => 'Settat'],
            
            // Sefrou
            ['commune' => 'Sidi Youssef Ben Ahmed', 'province' => 'Sefrou'],
            ['commune' => 'Laanoucer', 'province' => 'Sefrou'],
            ['commune' => 'Ait Sbaa Lajrouf', 'province' => 'Sefrou'],
            ['commune' => 'Tazouta', 'province' => 'Sefrou'],
            ['commune' => 'Ighzrane', 'province' => 'Sefrou'],
            
            // Salé
            ['commune' => 'Sehoul', 'province' => 'Salé'],
            ['commune' => 'Salé', 'province' => 'Salé'],
            ['commune' => 'Ameur', 'province' => 'Salé'],
            
            // Safi
            ['commune' => 'Dar si aissa', 'province' => 'Safi'],
            ['commune' => 'Maachate', 'province' => 'Safi'],
            ['commune' => 'Hrara', 'province' => 'Safi'],
            
            // Rabat
            ['commune' => 'Yacoub El Mansour', 'province' => 'Rabat'],
            ['commune' => 'Rabat', 'province' => 'Rabat'],
            
            // Oujda-Angad
            ['commune' => 'Beni Khaled', 'province' => 'Oujda-Angad'],
            ['commune' => 'Sidi Boulanoire', 'province' => 'Oujda-Angad'],
            ['commune' => 'Ahl Angad', 'province' => 'Oujda-Angad'],
            ['commune' => 'Sidi Moussa Lamhaya', 'province' => 'Oujda-Angad'],
            
            // Ouezzane
            ['commune' => 'Ain Baida', 'province' => 'Ouezzane'],
            ['commune' => 'Z\'ghira', 'province' => 'Ouezzane'],
            ['commune' => 'Ouenana', 'province' => 'Ouezzane'],
            ['commune' => 'M\'zefroun', 'province' => 'Ouezzane'],
            ['commune' => 'Zoumi', 'province' => 'Ouezzane'],
            ['commune' => 'Masmouda', 'province' => 'Ouezzane'],
            
            // Nouaceur
            ['commune' => 'Bouskoura', 'province' => 'Nouaceur'],
            
            // Nador
            ['commune' => 'Hassi Berkane', 'province' => 'Nador'],
            ['commune' => 'Beni Chiker', 'province' => 'Nador'],
            ['commune' => 'Beni Bouifrour', 'province' => 'Nador'],
            ['commune' => 'Iheddaden', 'province' => 'Nador'],
            ['commune' => 'Nador', 'province' => 'Nador'],
            ['commune' => 'Segangan', 'province' => 'Nador'],
            ['commune' => 'Beni Sidel Louta', 'province' => 'Nador'],
            
            // Mohammedia
            ['commune' => 'Mohammedia', 'province' => 'Mohammedia'],
            
            // Midelt
            ['commune' => 'Tanourdi', 'province' => 'Midelt'],
            ['commune' => 'Rich', 'province' => 'Midelt'],
            ['commune' => 'Gourrama', 'province' => 'Midelt'],
            ['commune' => 'Ait Izdeg', 'province' => 'Midelt'],
            ['commune' => 'Midelt', 'province' => 'Midelt'],
            ['commune' => 'Itzer', 'province' => 'Midelt'],
            ['commune' => 'Sidi Yahya Ouyoussef', 'province' => 'Midelt'],
            ['commune' => 'Tizi N\'Ghachou', 'province' => 'Midelt'],
            ['commune' => 'Tounfite', 'province' => 'Midelt'],
            ['commune' => 'Amercid', 'province' => 'Midelt'],
            ['commune' => 'Ait Ben Yaakoub', 'province' => 'Midelt'],
            ['commune' => 'Aghbalou', 'province' => 'Midelt'],
            
            // Meknès
            ['commune' => 'Sidi Abdellah El Khayat', 'province' => 'Meknès'],
            ['commune' => 'Lamghassiyine', 'province' => 'Meknès'],
            
            // Mediouna
            ['commune' => 'Mejjatia Old Taleb', 'province' => 'Mediouna'],
            
            // M'diq-Fnideq
            ['commune' => 'Alliyine', 'province' => 'M\'diq-Fnideq'],
            ['commune' => 'M\'diq', 'province' => 'M\'diq-Fnideq'],
            ['commune' => 'Ben Younech', 'province' => 'M\'diq-Fnideq'],
            ['commune' => 'Martil', 'province' => 'M\'diq-Fnideq'],
            
            // Marrakech
            ['commune' => 'Ouahat Sidi Brahim', 'province' => 'Marrakech'],
            
            // Larache
            ['commune' => 'Laouamra', 'province' => 'Larache'],
            ['commune' => 'Zaaroura', 'province' => 'Larache'],
            ['commune' => 'Zouada', 'province' => 'Larache'],
            ['commune' => 'Boujdiane', 'province' => 'Larache'],
            ['commune' => 'Beni Aros', 'province' => 'Larache'],
            ['commune' => 'Larache', 'province' => 'Larache'],
            ['commune' => 'Beni Gorfet', 'province' => 'Larache'],
            ['commune' => 'Souk El Kolla', 'province' => 'Larache'],
            ['commune' => 'Rissana Nord', 'province' => 'Larache'],
            ['commune' => 'Sahel', 'province' => 'Larache'],
            ['commune' => 'Tazrout', 'province' => 'Larache'],
            
            // Khouribga
            ['commune' => 'Boujaad', 'province' => 'Khouribga'],
            ['commune' => 'Bni Smir', 'province' => 'Khouribga'],
            ['commune' => 'Boukhrisse', 'province' => 'Khouribga'],
            ['commune' => 'Béni Zrantel', 'province' => 'Khouribga'],
            ['commune' => 'Oulad Fennane', 'province' => 'Khouribga'],
            
            // Khénifra
            ['commune' => 'El Borj', 'province' => 'Khénifra'],
            ['commune' => 'Moha ouhammou Zayani', 'province' => 'Khénifra'],
            ['commune' => 'Aguelmous', 'province' => 'Khénifra'],
            ['commune' => 'Lehri', 'province' => 'Khénifra'],
            ['commune' => 'El Hammam et Oum Erbiaa', 'province' => 'Khénifra'],
            ['commune' => 'Oum Erbiaa', 'province' => 'Khénifra'],
            ['commune' => 'Tighassaline', 'province' => 'Khénifra'],
            ['commune' => 'Aguelmame Azigza', 'province' => 'Khénifra'],
            
            // Khémisset
            ['commune' => 'Ait Mimoune', 'province' => 'Khémisset'],
            ['commune' => 'Ait Ali Oulahcen', 'province' => 'Khémisset'],
            ['commune' => 'Ain Johra Sidi Boukhalkhal', 'province' => 'Khémisset'],
            ['commune' => 'Mkam Tolba', 'province' => 'Khémisset'],
            ['commune' => 'Sidi Abderrazek', 'province' => 'Khémisset'],
            ['commune' => 'Ait Malek', 'province' => 'Khémisset'],
            ['commune' => 'Rommani', 'province' => 'Khémisset'],
            ['commune' => 'Brachoua', 'province' => 'Khémisset'],
            ['commune' => 'El Ganzra', 'province' => 'Khémisset'],
            ['commune' => 'Majmaa Tolba', 'province' => 'Khémisset'],
            ['commune' => 'Ait Ichou', 'province' => 'Khémisset'],
            ['commune' => 'Ait Siberne', 'province' => 'Khémisset'],
            ['commune' => 'Elgansa', 'province' => 'Khémisset'],
            ['commune' => 'Boukachmir', 'province' => 'Khémisset'],
            ['commune' => 'Oulmes', 'province' => 'Khémisset'],
            ['commune' => 'Had Brachoua', 'province' => 'Khémisset'],
            
            // Kénitra
            ['commune' => 'Kénitra', 'province' => 'Kénitra'],
            ['commune' => 'Arbaoua', 'province' => 'Kénitra'],
            ['commune' => 'Kariat Ben Aouda', 'province' => 'Kénitra'],
            ['commune' => 'Sidi Taibi', 'province' => 'Kénitra'],
            ['commune' => 'Sidi Boubker El Haj', 'province' => 'Kénitra'],
            ['commune' => 'Chouafae', 'province' => 'Kénitra'],
            ['commune' => 'Haddada', 'province' => 'Kénitra'],
            ['commune' => 'Ameur Seflia', 'province' => 'Kénitra'],
            ['commune' => 'Ouled Slama', 'province' => 'Kénitra'],
            ['commune' => 'Bahhara Ouled Ayad', 'province' => 'Kénitra'],
            ['commune' => 'Moulay Boussalham', 'province' => 'Kénitra'],
            ['commune' => 'Souk EL Arbaa', 'province' => 'Kénitra'],
            ['commune' => 'Mehdya', 'province' => 'Kénitra'],
            ['commune' => 'Beni Malek', 'province' => 'Kénitra'],
            ['commune' => 'Lalla Mimouna', 'province' => 'Kénitra'],
            
            // Kelaa sraghna
            ['commune' => 'Oulad el garne', 'province' => 'Kelaa sraghna'],
            ['commune' => 'Jbiyel', 'province' => 'Kelaa sraghna'],
            
            // Jerada
            ['commune' => 'Ain Béni Mathar', 'province' => 'Jerada'],
            ['commune' => 'Laaouinate', 'province' => 'Jerada'],
            
            // Inzegane Ait Melloul
            ['commune' => 'Inzegane', 'province' => 'Inzegane Ait Melloul'],
            ['commune' => 'Lakliaa', 'province' => 'Inzegane Ait Melloul'],
            ['commune' => 'Ait Melloul', 'province' => 'Inzegane Ait Melloul'],
            ['commune' => 'Amsekroud', 'province' => 'Inzegane Ait Melloul'],
            
            // Ifrane
            ['commune' => 'Sidi El Mekhfi', 'province' => 'Ifrane'],
            ['commune' => 'Ain Leuh', 'province' => 'Ifrane'],
            ['commune' => 'Tizguite', 'province' => 'Ifrane'],
            ['commune' => 'Ifrane', 'province' => 'Ifrane'],
            ['commune' => 'Oued Ifrane', 'province' => 'Ifrane'],
            ['commune' => 'Dayet Aoua', 'province' => 'Ifrane'],
            ['commune' => 'Bensmim', 'province' => 'Ifrane'],
            ['commune' => 'Timahdite', 'province' => 'Ifrane'],
            
            // Guercif
            ['commune' => 'Berkine', 'province' => 'Guercif'],
            
            // Fqih Ben Saleh
            ['commune' => 'Sidi Aissa Ben Ali', 'province' => 'Fqih Ben Saleh'],
            
            // Fahs-Anjra
            ['commune' => 'Melloussa', 'province' => 'Fahs-Anjra'],
            ['commune' => 'Taghramt', 'province' => 'Fahs-Anjra'],
            ['commune' => 'Ksar El Majaz', 'province' => 'Fahs-Anjra'],
            ['commune' => 'Ksar Sghir', 'province' => 'Fahs-Anjra'],
            
            // Essaouira
            ['commune' => 'Tidzi', 'province' => 'Essaouira'],
            ['commune' => 'Ounagha', 'province' => 'Essaouira'],
            ['commune' => 'Sidi Ahmed Oumbarek', 'province' => 'Essaouira'],
            
            // El Jadida
            ['commune' => 'Bouhmam', 'province' => 'El Jadida'],
            ['commune' => 'Boulaouane', 'province' => 'El Jadida'],
            ['commune' => 'Mharza Sahel', 'province' => 'El Jadida'],
            ['commune' => 'Haouzia', 'province' => 'El Jadida'],
            
            // El Hajeb
            ['commune' => 'Ait Naâmane', 'province' => 'El Hajeb'],
            ['commune' => 'Iquadar', 'province' => 'El Hajeb'],
            ['commune' => 'Ras Jerry', 'province' => 'El Hajeb'],
            ['commune' => 'Ait Ouikhalfen', 'province' => 'El Hajeb'],
            
            // Driouch
            ['commune' => 'Mtalssa', 'province' => 'Driouch'],
            ['commune' => 'Trogout', 'province' => 'Driouch'],
            ['commune' => 'Tsaft', 'province' => 'Driouch'],
            ['commune' => 'Ain Zohra', 'province' => 'Driouch'],
            ['commune' => 'Iferni', 'province' => 'Driouch'],
            ['commune' => 'Temsaman', 'province' => 'Driouch'],
            
            // Chichaoua
            ['commune' => 'Imintanoute', 'province' => 'Chichaoua'],
            
            // Chefchaoun
            ['commune' => 'Tanakob', 'province' => 'Chefchaoun'],
            ['commune' => 'Amtar', 'province' => 'Chefchaoun'],
            ['commune' => 'Bab Taza', 'province' => 'Chefchaoun'],
            ['commune' => 'M\'tioua', 'province' => 'Chefchaoun'],
            ['commune' => 'Beni Bouzra', 'province' => 'Chefchaoun'],
            ['commune' => 'Talambote', 'province' => 'Chefchaoun'],
            ['commune' => 'Beni Faghloum', 'province' => 'Chefchaoun'],
            ['commune' => 'Dardara', 'province' => 'Chefchaoun'],
            
            // Boulmane
            ['commune' => 'Imouzzer Marmoucha', 'province' => 'Boulmane'],
            ['commune' => 'Ait Bazza', 'province' => 'Boulmane'],
            ['commune' => 'Guigou', 'province' => 'Boulmane'],
            ['commune' => 'Almis Marmoucha', 'province' => 'Boulmane'],
            ['commune' => 'El Marsse', 'province' => 'Boulmane'],
            
            // Berkane
            ['commune' => 'Rislane', 'province' => 'Berkane'],
            ['commune' => 'Boughriba', 'province' => 'Berkane'],
            ['commune' => 'Tafoughalt', 'province' => 'Berkane'],
            ['commune' => 'Sidi Bouhria', 'province' => 'Berkane'],
            ['commune' => 'Saidia', 'province' => 'Berkane'],
            ['commune' => 'Sidi Slimane Cherraa', 'province' => 'Berkane'],
            ['commune' => 'Chouihiya', 'province' => 'Berkane'],
            
            // Benslimane
            ['commune' => 'Mellila', 'province' => 'Benslimane'],
            ['commune' => 'Bir Annasr', 'province' => 'Benslimane'],
            ['commune' => 'Ziaida', 'province' => 'Benslimane'],
            ['commune' => 'Ain Tizgha', 'province' => 'Benslimane'],
            ['commune' => 'Mansouria', 'province' => 'Benslimane'],
            ['commune' => 'Benslimane', 'province' => 'Benslimane'],
            
            // Béni Mellal
            ['commune' => 'Tanogha', 'province' => 'Béni Mellal'],
            ['commune' => 'Dir El Ksiba', 'province' => 'Béni Mellal'],
            ['commune' => 'Foum EL Anceur', 'province' => 'Béni Mellal'],
            ['commune' => 'Semguet', 'province' => 'Béni Mellal'],
            ['commune' => 'Tizi N\'Isly', 'province' => 'Béni Mellal'],
            ['commune' => 'Boutferda', 'province' => 'Béni Mellal'],
            ['commune' => 'Naour', 'province' => 'Béni Mellal'],
            ['commune' => 'Ait Oum EL Bakht', 'province' => 'Béni Mellal'],
            ['commune' => 'Aghbala', 'province' => 'Béni Mellal'],
            ['commune' => 'Foum Oudi', 'province' => 'Béni Mellal'],
            
            // Azilal
            ['commune' => 'Ait Mazigh', 'province' => 'Azilal'],
            ['commune' => 'Demnate', 'province' => 'Azilal'],
            ['commune' => 'Issekssi', 'province' => 'Azilal'],
            ['commune' => 'Ait Ouaarda', 'province' => 'Azilal'],
            ['commune' => 'Timoulite', 'province' => 'Azilal'],
            ['commune' => 'Tillouguite', 'province' => 'Azilal'],
            ['commune' => 'Ouaouizerth', 'province' => 'Azilal'],
            ['commune' => 'Tabaroucht et Ait Mazigh', 'province' => 'Azilal'],
            ['commune' => 'Agoudi N\'El khir', 'province' => 'Azilal'],
            ['commune' => 'Afourar', 'province' => 'Azilal'],
            ['commune' => 'Bin Elouidane', 'province' => 'Azilal'],
            ['commune' => 'Taounza', 'province' => 'Azilal'],
            ['commune' => 'Tanant', 'province' => 'Azilal'],
            ['commune' => 'Tabia', 'province' => 'Azilal'],
            ['commune' => 'Tifni', 'province' => 'Azilal'],
            ['commune' => 'Tamda Noumercid', 'province' => 'Azilal'],
            ['commune' => 'Tabaroucht', 'province' => 'Azilal'],
            ['commune' => 'Ait M\'Hamed', 'province' => 'Azilal'],
            ['commune' => 'Taguelft', 'province' => 'Azilal'],
            ['commune' => 'Ait Abbas', 'province' => 'Azilal'],
            ['commune' => 'Ait Taguella', 'province' => 'Azilal'],
            
            // Al Youssoufia
            ['commune' => 'Jnan bouih', 'province' => 'Al Youssoufia'],
            
            // Al Hoceima
            ['commune' => 'Rouadi', 'province' => 'Al Hoceima'],
            ['commune' => 'Beni Bounsar', 'province' => 'Al Hoceima'],
            ['commune' => 'Zerkat', 'province' => 'Al Hoceima'],
            ['commune' => 'Abdelghaya Souahel', 'province' => 'Al Hoceima'],
            ['commune' => 'Issaguene', 'province' => 'Al Hoceima'],
            ['commune' => 'Beni Bchir', 'province' => 'Al Hoceima'],
            ['commune' => 'Beni Boufrah', 'province' => 'Al Hoceima'],
            ['commune' => 'Chakrane', 'province' => 'Al Hoceima'],
            ['commune' => 'Targuist', 'province' => 'Al Hoceima'],
            ['commune' => 'Sidi Boutmim', 'province' => 'Al Hoceima'],
            ['commune' => 'Beni Ammart', 'province' => 'Al Hoceima'],
            
            // Al Haouz
            ['commune' => 'Oukaimden', 'province' => 'Al Haouz'],
            ['commune' => 'Ouirgane', 'province' => 'Al Haouz'],
            ['commune' => 'Aghouatim', 'province' => 'Al Haouz'],
            ['commune' => 'Moulay Brahim', 'province' => 'Al Haouz'],
            ['commune' => 'Asni', 'province' => 'Al Haouz'],
            ['commune' => 'Talat n\'yaakoub', 'province' => 'Al Haouz'],
            ['commune' => 'Tamaguert', 'province' => 'Al Haouz'],
            ['commune' => 'Ijoukak', 'province' => 'Al Haouz'],
            ['commune' => 'Imegdal', 'province' => 'Al Haouz'],
            ['commune' => 'Setti Fatma', 'province' => 'Al Haouz'],
            ['commune' => 'Ourika', 'province' => 'Al Haouz'],
            ['commune' => 'Ait Adel', 'province' => 'Al Haouz'],
            ['commune' => 'Tidili Mesfioua', 'province' => 'Al Haouz'],
            ['commune' => 'Amazmiz', 'province' => 'Al Haouz'],
        ];

        foreach ($situations as $situation) {
            SituationAdministrative::firstOrCreate(
                ['commune' => $situation['commune']],
                $situation
            );
        }
    }
}
