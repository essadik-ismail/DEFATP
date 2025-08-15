@extends('layouts.app')

@section('title', 'Démo du Composant Select avec Recherche')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-search me-2"></i>Démo du Composant Select avec Recherche
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Cette page démontre le composant select avec fonctionnalité de recherche, similaire à Select2.
                        Testez les différentes configurations et fonctionnalités.
                    </div>
                    
                    <form>
                        <div class="row">
                            <!-- Single Select with Search -->
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Select Simple avec Recherche</h6>
                                    </div>
                                    <div class="card-body">
                                        <x-form.select-search
                                            name="country"
                                            label="Pays"
                                            :options="[
                                                'ma' => 'Maroc',
                                                'dz' => 'Algérie',
                                                'tn' => 'Tunisie',
                                                'ly' => 'Libye',
                                                'eg' => 'Égypte',
                                                'sd' => 'Soudan',
                                                'et' => 'Éthiopie',
                                                'ke' => 'Kenya',
                                                'ng' => 'Nigeria',
                                                'za' => 'Afrique du Sud',
                                                'gh' => 'Ghana',
                                                'ci' => 'Côte d\'Ivoire',
                                                'sn' => 'Sénégal',
                                                'ml' => 'Mali',
                                                'bf' => 'Burkina Faso',
                                                'ne' => 'Niger',
                                                'td' => 'Tchad',
                                                'cm' => 'Cameroun',
                                                'cf' => 'République centrafricaine',
                                                'cg' => 'République du Congo'
                                            ]"
                                            placeholder="Choisissez un pays..."
                                            searchPlaceholder="Rechercher un pays..."
                                            required="true"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Multiple Select with Search -->
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Select Multiple avec Recherche</h6>
                                    </div>
                                    <div class="card-body">
                                        <x-form.select-search
                                            name="languages"
                                            label="Langues Parlées"
                                            :options="[
                                                'ar' => 'Arabe',
                                                'fr' => 'Français',
                                                'en' => 'Anglais',
                                                'es' => 'Espagnol',
                                                'pt' => 'Portugais',
                                                'de' => 'Allemand',
                                                'it' => 'Italien',
                                                'ru' => 'Russe',
                                                'zh' => 'Chinois',
                                                'ja' => 'Japonais',
                                                'ko' => 'Coréen',
                                                'hi' => 'Hindi',
                                                'bn' => 'Bengali',
                                                'ur' => 'Ourdou',
                                                'fa' => 'Persan',
                                                'tr' => 'Turc',
                                                'nl' => 'Néerlandais',
                                                'pl' => 'Polonais',
                                                'cs' => 'Tchèque',
                                                'sv' => 'Suédois'
                                            ]"
                                            :multiple="true"
                                            placeholder="Choisissez des langues..."
                                            searchPlaceholder="Rechercher des langues..."
                                            :maxItems="5"
                                            helpText="Sélectionnez jusqu'à 5 langues maximum"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Large Options List -->
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Liste d'Options Étendue</h6>
                                    </div>
                                    <div class="card-body">
                                        <x-form.select-search
                                            name="cities"
                                            label="Villes du Maroc"
                                            :options="[
                                                'casablanca' => 'Casablanca',
                                                'rabat' => 'Rabat',
                                                'fes' => 'Fès',
                                                'marrakech' => 'Marrakech',
                                                'agadir' => 'Agadir',
                                                'tangier' => 'Tanger',
                                                'meknes' => 'Meknès',
                                                'oujda' => 'Oujda',
                                                'kenitra' => 'Kénitra',
                                                'tetouan' => 'Tétouan',
                                                'safi' => 'Safi',
                                                'el_jadida' => 'El Jadida',
                                                'beni_mellal' => 'Béni Mellal',
                                                'taza' => 'Taza',
                                                'larache' => 'Larache',
                                                'khemisset' => 'Khemisset',
                                                'taourirt' => 'Taourirt',
                                                'berkane' => 'Berkane',
                                                'sidi_kacem' => 'Sidi Kacem',
                                                'khouribga' => 'Khouribga',
                                                'tiflet' => 'Tiflet',
                                                'sidi_slimane' => 'Sidi Slimane',
                                                'ouezzane' => 'Ouazzane',
                                                'gueznaya' => 'Gueznaya',
                                                'mohammedia' => 'Mohammedia',
                                                'temara' => 'Témara',
                                                'skhirat' => 'Skhirate',
                                                'sale' => 'Salé',
                                                'ifrane' => 'Ifrane',
                                                'azrou' => 'Azrou',
                                                'midelt' => 'Midelt',
                                                'er_rachidia' => 'Er Rachidia',
                                                'ouarzazate' => 'Ouarzazate',
                                                'taroudant' => 'Taroudant',
                                                'tiznit' => 'Tiznit',
                                                'essaouira' => 'Essaouira',
                                                'youssoufia' => 'Youssoufia',
                                                'sidi_bennour' => 'Sidi Bennour',
                                                'berrechid' => 'Berrechid',
                                                'settat' => 'Settat',
                                                'khouribga' => 'Khouribga',
                                                'benguerir' => 'Benguerir',
                                                'youssoufia' => 'Youssoufia'
                                            ]"
                                            placeholder="Choisissez une ville..."
                                            searchPlaceholder="Rechercher une ville..."
                                            helpText="Recherchez parmi plus de 40 villes marocaines"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Pre-selected Values -->
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Valeurs Pré-sélectionnées</h6>
                                    </div>
                                    <div class="card-body">
                                        <x-form.select-search
                                            name="pre_selected"
                                            label="Options Pré-sélectionnées"
                                            :options="[
                                                'option1' => 'Première Option',
                                                'option2' => 'Deuxième Option',
                                                'option3' => 'Troisième Option',
                                                'option4' => 'Quatrième Option',
                                                'option5' => 'Cinquième Option'
                                            ]"
                                            selected="option2"
                                            placeholder="Choisissez une option..."
                                            searchPlaceholder="Rechercher..."
                                        />

                                        <hr class="my-3">

                                        <x-form.select-search
                                            name="pre_selected_multiple"
                                            label="Options Multiples Pré-sélectionnées"
                                            :options="[
                                                'item1' => 'Élément 1',
                                                'item2' => 'Élément 2',
                                                'item3' => 'Élément 3',
                                                'item4' => 'Élément 4',
                                                'item5' => 'Élément 5'
                                            ]"
                                            :selected="['item1', 'item3']"
                                            :multiple="true"
                                            placeholder="Choisissez des éléments..."
                                            searchPlaceholder="Rechercher..."
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Form Submission Demo -->
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Démonstration de Soumission de Formulaire</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <x-form.select-search
                                                    name="category"
                                                    label="Catégorie"
                                                    :options="[
                                                        'tech' => 'Technologie',
                                                        'health' => 'Santé',
                                                        'education' => 'Éducation',
                                                        'finance' => 'Finance',
                                                        'entertainment' => 'Divertissement'
                                                    ]"
                                                    placeholder="Choisissez une catégorie..."
                                                    required="true"
                                                />
                                            </div>
                                            <div class="col-md-4">
                                                <x-form.select-search
                                                    name="tags"
                                                    label="Tags"
                                                    :options="[
                                                        'web' => 'Développement Web',
                                                        'mobile' => 'Applications Mobiles',
                                                        'ai' => 'Intelligence Artificielle',
                                                        'cloud' => 'Cloud Computing',
                                                        'security' => 'Cybersécurité',
                                                        'data' => 'Science des Données',
                                                        'blockchain' => 'Blockchain',
                                                        'iot' => 'Internet des Objets'
                                                    ]"
                                                    :multiple="true"
                                                    placeholder="Choisissez des tags..."
                                                    :maxItems="3"
                                                    helpText="Maximum 3 tags"
                                                />
                                            </div>
                                            <div class="col-md-4">
                                                <x-form.select-search
                                                    name="priority"
                                                    label="Priorité"
                                                    :options="[
                                                        'low' => 'Faible',
                                                        'medium' => 'Moyenne',
                                                        'high' => 'Élevée',
                                                        'urgent' => 'Urgente'
                                                    ]"
                                                    placeholder="Choisissez une priorité..."
                                                />
                                            </div>
                                        </div>

                                        <div class="text-center mt-4">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="fas fa-paper-plane me-2"></i>Soumettre le Formulaire
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Features List -->
                    <div class="mt-5">
                        <h5>Fonctionnalités du Composant :</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>Recherche en temps réel</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Sélection simple et multiple</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Limitation du nombre d'éléments</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Valeurs pré-sélectionnées</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Placeholder personnalisable</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>Navigation au clavier (Échap)</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Fermeture automatique au clic extérieur</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Design responsive et accessible</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Intégration parfaite avec Laravel</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Validation et gestion d'erreurs</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Demo form submission
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        
        console.log('Form Data:', data);
        
        // Show success message
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success alert-dismissible fade show';
        alertDiv.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>
            <strong>Formulaire soumis avec succès!</strong> Vérifiez la console pour voir les données.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.querySelector('.card-body').insertBefore(alertDiv, document.querySelector('.alert-info'));
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    });
</script>
@endsection
