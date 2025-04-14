@extends('layouts.app')

@section('content')
    <div class="dashboard-container">
        <div class="overlay"></div> <!-- Effet de transparence -->
        <div class="container">
            <h1 class="my-4 text-white fw-bold text-center">📊 Tableau de Bord</h1>

            <!-- Statistiques générales avec effet de transparence -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-lg bg-light-transparent">
                        <div class="card-header fw-semibold text-primary">📅 Commandes du jour</div>
                        <div class="card-body text-dark text-center">
                            <h5 class="card-title display-4 fw-bold">{{ $commandesDuJour ?? '0' }}</h5>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-lg bg-light-transparent">
                        <div class="card-header fw-semibold text-success">✅ Commandes validées</div>
                        <div class="card-body text-dark text-center">
                            <h5 class="card-title display-4 fw-bold">{{ $commandesValidees ?? '0' }}</h5>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-lg bg-light-transparent">
                        <div class="card-header fw-semibold text-danger">💰 Recettes du jour</div>
                        <div class="card-body text-dark text-center">
                            <h5 class="card-title display-4 fw-bold">{{ number_format($recettes ?? 0, 2, ',', ' ') }} FCFA</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphiques -->
            <h2 class="mb-3 text-white fw-semibold text-center">📈 Statistiques Graphiques</h2>
            <div class="row">
                <!-- Commandes par mois -->
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-lg bg-light-transparent">
                        <div class="card-header fw-semibold text-secondary">📅 Commandes par Mois</div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="commandesParMoisChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ventes par catégorie -->
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-lg bg-light-transparent">
                        <div class="card-header fw-semibold text-secondary">📚 Ventes par Catégorie</div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="livresParCategorieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inclure Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var commandesParMoisLabels = @json(array_keys($commandesParMois ?? []));
        var commandesParMoisData = @json(array_values($commandesParMois ?? []));

        var ctx1 = document.getElementById('commandesParMoisChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: commandesParMoisLabels,
                datasets: [{
                    label: 'Commandes par mois',
                    data: commandesParMoisData,
                    backgroundColor: 'rgba(33, 150, 243, 0.6)',
                    borderColor: 'rgba(25, 118, 210, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });

        var livresParCategorieLabels = @json(array_keys($livresParCategorie ?? []));
        var livresParCategorieData = @json(array_values($livresParCategorie ?? []));

        var ctx2 = document.getElementById('livresParCategorieChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: livresParCategorieLabels,
                datasets: [{
                    label: 'Ventes par catégorie',
                    data: livresParCategorieData,
                    backgroundColor: ['#6C8EBF', '#90CAF9', '#C5CAE9', '#B0BEC5'],
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    </script>

    <style>
        /* Image de fond */
        .dashboard-container {
            position: relative;
            background: url('https://source.unsplash.com/1600x900/?business,technology') center/cover no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6); /* Effet de transparence */
        }

        .container {
            position: relative;
            z-index: 2;
        }

        /* Style des cartes avec transparence */
        .bg-light-transparent {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
        }

        .chart-container { width: 100%; height: 300px; }
        .card-header { font-size: 1.2rem; font-weight: bold; }
        .card-title { font-size: 2rem; }
    </style>
@endsection
