<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demande de remboursement de frais de transport</title>

    @php
        $employe = $demande->user;
        $moyens = [
            'depart' => $demande->lieu_depart,
            'arrivee' => $demande->lieu_arrivee,
        ];
    @endphp

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.6;
            color: #000;
            padding: 2.2cm 2cm 2.5cm 2.2cm;
            background: #fff;
        }

        .header-logo {
            margin-bottom: 1.6rem;
        }
        .header-logo img {
            max-width: 260px;
            height: auto;
        }
        .header-bloc {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
        }
        .sender {
            font-size: 9pt;
            line-height: 1.5;
            text-align: left;
        }
        .sender strong {
            font-size: 10pt;
        }
        .email-blue {
            color: #1B3A6B;
        }

        .right-bloc {
            text-align: right;
            font-size: 10pt;
            line-height: 1.6;
        }
        .date-line {
            margin-bottom: 1.2rem;
        }

        .objet {
            margin: 1.5rem 0 1.8rem 0;
            font-size: 10pt;
            font-weight: bold;
        }

        .content {
            text-align: justify;
            font-size: 10pt;
            line-height: 1.6;
        }
        .content p {
            margin-bottom: 1rem;
        }

        .recap {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0 1.5rem 0;
        }
        .recap td {
            padding: 5px 8px;
            border: 1px solid #cbd5e1;
            font-size: 9.5pt;
        }
        .recap td:first-child {
            width: 40%;
            color: #6B7280;
            background: #f8fafc;
        }

        .signature {
            margin-top: 2.5rem;
            text-align: right;
            font-size: 10pt;
            line-height: 1.8;
        }

        .badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 10px;
            font-size: 8.5pt;
            font-weight: bold;
        }
        .badge-en_attente { background: #fef3c7; color: #b45309; }
        .badge-validee { background: #d1fae5; color: #047857; }
        .badge-rejetee { background: #fee2e2; color: #b91c1c; }
    </style>
</head>
<body>

    <div class="header-logo">
        <img src="{{ public_path('logo_cofima_bon.jpg') }}" alt="Logo COFIMA">
    </div>

    <div class="header-bloc">
        <div class="sender">
            <strong>{{ $employe->prenom }} {{ strtoupper($employe->nom) }}</strong><br>
            @if ($employe->poste?->intitule)
                <strong>Poste : </strong>{{ $employe->poste->intitule }}<br>
            @endif
            @if ($employe->telephone)
                Téléphone : {{ $employe->telephone }}<br>
            @endif
            @if ($employe->email)
                <strong>E-mail : <span class="email-blue">{{ $employe->email }}</span></strong>
            @endif
        </div>

        <div class="right-bloc">
            <div class="date-line">
                Cotonou, le {{ $demande->created_at->isoFormat('D MMMM YYYY') }}
            </div>
            <div>
                À<br>
                <strong>Madame, Monsieur le Directeur Général</strong><br>
                du Cabinet COFIMA
            </div>
        </div>
    </div>

    <div class="objet">
        <strong style="text-decoration: underline;">Objet :</strong> Demande de remboursement de frais de transport
    </div>

    <div class="content">
        <p>Madame, Monsieur le Directeur Général,</p>

        <p>
            Je me permets par la présente de solliciter le remboursement des frais de transport
            engagés dans le cadre de l&apos;exercice de mes fonctions au sein du cabinet.
        </p>

        <p>
            En effet, je me suis déplacé{{ $employe->sexe === 'F' ? 'e' : '' }} de <strong>{{ $moyens['depart'] }}</strong>
            à <strong>{{ $moyens['arrivee'] }}</strong> le <strong>{{ $demande->date_deplacement->isoFormat('D MMMM YYYY') }}</strong>,
            en <strong>{{ $demande->moyen_transport }}</strong>, dans le cadre de : {{ lcfirst($demande->motif) }}.
        </p>

        <table class="recap">
            <tr>
                <td>Trajet</td>
                <td>{{ $moyens['depart'] }} → {{ $moyens['arrivee'] }}</td>
            </tr>
            <tr>
                <td>Date du déplacement</td>
                <td>{{ $demande->date_deplacement->isoFormat('D MMMM YYYY') }}</td>
            </tr>
            <tr>
                <td>Moyen de transport</td>
                <td>{{ $demande->moyen_transport }}</td>
            </tr>
            <tr>
                <td>Coût estimé</td>
                <td>{{ number_format($demande->cout_estime, 0, ',', ' ') }} FCFA</td>
            </tr>
            @if ($demande->commentaire)
                <tr>
                    <td>Commentaire</td>
                    <td>{{ $demande->commentaire }}</td>
                </tr>
            @endif
            <tr>
                <td>Statut</td>
                <td>
                    <span class="badge badge-{{ $demande->statut }}">
                        {{ match ($demande->statut) {
                            'en_attente' => 'En attente',
                            'validee' => 'Validée',
                            'rejetee' => 'Rejetée',
                        } }}
                    </span>
                    @if ($demande->statut === 'rejetee' && $demande->motif_rejet)
                        — {{ $demande->motif_rejet }}
                    @endif
                </td>
            </tr>
        </table>

        <p>
            Je vous saurais gré de bien vouloir examiner favorablement cette demande et d&apos;autoriser
            le remboursement du montant susmentionné.
        </p>

        <p>
            Je vous prie d&apos;agréer, Madame, Monsieur le Directeur Général, l&apos;expression de mes
            salutations distinguées.
        </p>
    </div>

    <br><br>
    <div class="signature">
        {{ $employe->prenom }} {{ strtoupper($employe->nom) }}
    </div>

</body>
</html>
