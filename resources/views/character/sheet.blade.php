@php
    use App\Misc\CharacterCreation;
    use App\Misc\CharacterSheet;

    $half   = fn (?int $value) => CharacterCreation::half((int) $value);
    $fifth  = fn (?int $value) => CharacterCreation::fifth((int) $value);
    $backstory = $character->backstory ?? [];

    $maxHitPoints = CharacterCreation::hitPoints($character);
    $maxSanity    = CharacterSheet::maxSanity($character);
    $dodge        = CharacterSheet::dodge($character);
    $brawl        = CharacterSheet::skillValue($character, 'fighting-brawl') ?? 25;

    $storyFields = [
        'personal_description'  => 'Personal Description',
        'ideology'              => 'Ideology & Beliefs',
        'significant_people'    => 'Significant People',
        'meaningful_locations'  => 'Meaningful Locations',
        'treasured_possessions' => 'Treasured Possessions',
        'traits'                => 'Traits',
        'injuries_scars'        => 'Injuries & Scars',
        'phobias_manias'        => 'Phobias & Manias',
    ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $character->name }} — Investigator Sheet</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Limelight&display=swap" rel="stylesheet">

    <style>
        /* ---------------------------------------------------------------
           The printed investigator sheet.

           Deliberately hand-written CSS rather than the app's Tailwind build:
           the sheet is laid out in millimetres against a fixed A4 page, which
           is a different problem from the phone-first utility classes.
           Palette and typography still come from the app's design system.
        --------------------------------------------------------------- */
        :root {
            --green-950: #071711;
            --green-900: #0C251C;
            --green-800: #12372A;
            --green-500: #2F5540;
            --green-200: #ADBC9F;
            --parch-50:  #FDFBF3;
            --parch-100: #F7F2E1;
            --parch-200: #EDE4CB;
            --parch-300: #DCD1AF;
            --parch-400: #C3B58B;
            --brass-500: #D9A93F;
            --brass-700: #A87A33;
            --blood-400: #A0153E;
        }

        @page { size: A4 portrait; margin: 0; }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html { background: var(--green-950); }

        body {
            font-family: Figtree, ui-sans-serif, system-ui, sans-serif;
            color: var(--green-950);
            font-size: 8.2pt;
            line-height: 1.25;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .sheet {
            position: relative;
            width: 210mm;
            height: 297mm;
            padding: 7mm;
            margin: 0 auto;
            background: var(--parch-50);
            overflow: hidden;
        }

        .sheet + .sheet { margin-top: 8mm; }

        /* The dark green frame the whole app is held in. */
        .frame {
            position: relative;
            height: 100%;
            padding: 4mm;
            border: 1.1mm solid var(--green-800);
            outline: 0.3mm solid var(--brass-700);
            outline-offset: 1.1mm;
            display: flex;
            flex-direction: column;
            gap: 2.2mm;
        }

        .corner { position: absolute; width: 9mm; height: 9mm; color: var(--brass-700); }
        .corner-tl { top: 1mm; left: 1mm; }
        .corner-tr { top: 1mm; right: 1mm; transform: scaleX(-1); }
        .corner-bl { bottom: 1mm; left: 1mm; transform: scaleY(-1); }
        .corner-br { bottom: 1mm; right: 1mm; transform: scale(-1); }

        /* --- Typography ------------------------------------------------ */
        .display { font-family: Limelight, Georgia, serif; font-weight: 400; letter-spacing: 0.02em; }

        .eyebrow {
            font-size: 6pt;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            font-weight: 600;
            color: var(--green-500);
        }

        .eyebrow-on-dark { color: var(--brass-500); }

        .tabular { font-variant-numeric: tabular-nums; }

        /* --- Section headings ------------------------------------------ */
        .rule {
            display: flex;
            align-items: center;
            gap: 2mm;
            margin-bottom: 1.4mm;
        }

        .rule::after {
            content: '';
            flex: 1;
            height: 0.3mm;
            background: linear-gradient(to right, var(--brass-700), rgba(168, 122, 51, 0));
        }

        .rule h2 {
            font-family: Limelight, Georgia, serif;
            font-size: 8.5pt;
            font-weight: 400;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--green-800);
            white-space: nowrap;
        }

        /* --- Masthead --------------------------------------------------- */
        .masthead {
            display: grid;
            grid-template-columns: 1fr 30mm;
            gap: 4mm;
            background: var(--green-900);
            color: var(--parch-100);
            padding: 2.4mm 3.5mm;
            border-radius: 1mm;
        }

        .masthead h1 {
            font-family: Limelight, Georgia, serif;
            font-size: 18pt;
            font-weight: 400;
            line-height: 1.05;
            color: var(--parch-50);
            margin: 0.6mm 0 1mm;
        }

        .masthead .occupation {
            font-size: 9pt;
            color: var(--brass-500);
            font-weight: 600;
            letter-spacing: 0.03em;
        }

        .portrait {
            width: 30mm;
            height: 30mm;
            border: 0.5mm solid var(--brass-700);
            border-radius: 0.8mm;
            object-fit: cover;
            background: var(--green-800);
        }

        .portrait-empty {
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--green-500);
            font-size: 6pt;
            text-align: center;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        /* --- Identity strip --------------------------------------------- */
        .identity {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 2mm;
        }

        .field {
            border-bottom: 0.3mm solid var(--parch-400);
            padding-bottom: 0.6mm;
            min-height: 6.4mm;
        }

        .field .value { font-size: 9pt; font-weight: 600; color: var(--green-900); }
        .field .value.blank { color: var(--parch-400); }

        /* --- Characteristics -------------------------------------------- */
        .characteristics {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.8mm;
        }

        .char {
            display: flex;
            align-items: stretch;
            border: 0.3mm solid var(--parch-300);
            border-radius: 1mm;
            background: var(--parch-100);
            overflow: hidden;
        }

        .char .name {
            width: 12mm;
            background: var(--green-800);
            color: var(--parch-50);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.4mm 0;
        }

        .char .name b { font-family: Limelight, Georgia, serif; font-size: 9.5pt; font-weight: 400; }
        .char .name span { font-size: 5pt; letter-spacing: 0.1em; text-transform: uppercase; color: var(--brass-500); }

        .char .cells { flex: 1; display: grid; grid-template-columns: 1.5fr 1fr 1fr; }

        .cell {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1mm 0 0.8mm;
            border-left: 0.3mm solid var(--parch-300);
        }

        .cell:first-child { border-left: 0; }
        .cell .cap { font-size: 5pt; letter-spacing: 0.08em; text-transform: uppercase; color: var(--green-500); }
        .cell .num { font-size: 11pt; font-weight: 700; line-height: 1.05; }
        .cell.minor .num { font-size: 8.5pt; font-weight: 600; color: var(--green-500); }

        /* --- Vitals ------------------------------------------------------ */
        .vitals { display: grid; grid-template-columns: repeat(8, 1fr); gap: 1.8mm; }

        .vital {
            border: 0.3mm solid var(--parch-300);
            border-radius: 1mm;
            background: var(--parch-100);
            text-align: center;
            padding: 1.2mm 0.5mm 1.4mm;
        }

        .vital.marked { border-color: var(--brass-500); background: #FBF5E4; }
        .vital .cap { font-size: 5.2pt; letter-spacing: 0.07em; text-transform: uppercase; color: var(--green-500); display: block; }
        .vital .num { font-size: 13pt; font-weight: 700; line-height: 1.15; }
        .vital .sub { font-size: 5.5pt; color: var(--green-500); display: block; }

        /* --- Conditions --------------------------------------------------- */
        .conditions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2mm;
            background: var(--green-900);
            border-radius: 1mm;
            padding: 1.8mm 3mm;
            color: var(--parch-100);
        }

        .condition { display: flex; align-items: center; gap: 1.4mm; font-size: 7.4pt; letter-spacing: 0.02em; }

        .tick {
            width: 3.2mm;
            height: 3.2mm;
            border: 0.3mm solid var(--parch-300);
            border-radius: 0.4mm;
            display: inline-block;
            flex: none;
        }

        .tick.on { background: var(--blood-400); border-color: var(--blood-400); position: relative; }

        .tick.on::after {
            content: '✕';
            position: absolute;
            inset: 0;
            color: var(--parch-50);
            font-size: 6pt;
            line-height: 3.2mm;
            text-align: center;
        }

        /* --- Skills ------------------------------------------------------- */
        .skills { flex: 1; display: grid; grid-template-columns: repeat(3, 1fr); gap: 0 3mm; align-content: start; }

        /* Rows spread to fill the column, so the block reads as one solid table. */
        .skill-col { display: flex; flex-direction: column; justify-content: space-between; }

        .skill {
            display: grid;
            grid-template-columns: 3mm 1fr 7mm 5.4mm 5.4mm;
            align-items: center;
            gap: 0.8mm;
            padding: 0.2mm 0;
            border-bottom: 0.25mm dotted var(--parch-400);
        }

        .skill .box {
            width: 3mm;
            height: 3mm;
            border: 0.3mm solid var(--green-500);
            border-radius: 0.3mm;
        }

        .skill .box.on { background: var(--brass-500); border-color: var(--brass-700); }

        .skill .label { font-size: 7.2pt; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .skill .label i { font-style: normal; color: var(--parch-400); font-size: 6pt; }

        .skill .reg {
            text-align: center;
            font-size: 8.2pt;
            font-weight: 700;
            background: var(--parch-200);
            border-radius: 0.4mm;
        }

        .skill .reg.trained { background: var(--brass-500); }
        .skill .frac { text-align: center; font-size: 6.6pt; color: var(--green-500); }

        .skill-head {
            display: grid;
            grid-template-columns: 3mm 1fr 7mm 5.4mm 5.4mm;
            gap: 0.8mm;
            font-size: 5pt;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--green-500);
            text-align: center;
            padding-bottom: 0.4mm;
        }

        /* --- Combat -------------------------------------------------------- */
        table { width: 100%; border-collapse: collapse; }

        .combat th {
            font-size: 5.4pt;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--brass-500);
            background: var(--green-900);
            padding: 1.1mm 1.6mm;
            text-align: left;
            font-weight: 600;
        }

        .combat td {
            font-size: 7.4pt;
            padding: 0.95mm 1.6mm;
            border-bottom: 0.25mm solid var(--parch-300);
        }

        .combat tbody tr:nth-child(even) { background: var(--parch-100); }
        .combat td.n { text-align: center; font-weight: 600; }

        /* --- Page two ------------------------------------------------------ */
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 3mm 5mm; }

        .entry { break-inside: avoid; }
        .entry .label { font-size: 6.2pt; letter-spacing: 0.1em; text-transform: uppercase; color: var(--brass-700); font-weight: 600; }
        .entry .text { font-size: 7.6pt; white-space: pre-line; min-height: 4mm; padding-top: 0.4mm; }

        /* Empty entries print as ruled lines, so the sheet is worth carrying a pen to. */
        .ruled { display: flex; flex-direction: column; padding-top: 1mm; }
        .ruled span { display: block; height: 4.4mm; border-bottom: 0.25mm solid var(--parch-300); }

        .panel {
            border: 0.3mm solid var(--parch-300);
            border-radius: 1mm;
            background: var(--parch-100);
            padding: 2.2mm 2.6mm;
        }

        .wealth-row { display: flex; justify-content: space-between; border-bottom: 0.25mm dotted var(--parch-400); padding: 0.9mm 0; font-size: 7.6pt; }
        .wealth-row b { font-weight: 700; }

        .fellows { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2mm; }

        .fellow { border: 0.3mm solid var(--parch-300); border-radius: 1mm; padding: 1.6mm 2mm; background: var(--parch-100); }
        .fellow .who { font-size: 7.8pt; font-weight: 700; }
        .fellow .what { font-size: 6.4pt; color: var(--green-500); }

        .quickref { display: grid; grid-template-columns: 1fr 1fr; gap: 3mm; }
        .quickref h3 { font-size: 6.4pt; letter-spacing: 0.1em; text-transform: uppercase; color: var(--brass-700); margin-bottom: 1mm; }
        .quickref li { font-size: 6.9pt; list-style: none; padding: 0.5mm 0; border-bottom: 0.25mm dotted var(--parch-300); }
        .quickref li b { color: var(--green-800); }

        .success-table { width: 100%; border-collapse: collapse; }
        .success-table th { font-size: 5.4pt; text-transform: uppercase; letter-spacing: 0.05em; background: var(--green-900); color: var(--brass-500); padding: 0.9mm 0.5mm; }
        .success-table td { font-size: 6.8pt; text-align: center; padding: 0.9mm 0.5mm; border-bottom: 0.25mm solid var(--parch-300); }

        .colophon {
            display: flex;
            justify-content: space-between;
            font-size: 5.6pt;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--parch-400);
            padding: 1mm 7mm 0;
        }

        .push { margin-top: auto; }

        /* --- Screen-only controls -------------------------------------------- */
        .toolbar {
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            justify-content: center;
            gap: 3mm;
            padding: 10px;
            background: var(--green-950);
        }

        .toolbar button, .toolbar a {
            font-family: inherit;
            font-size: 13px;
            font-weight: 600;
            padding: 8px 18px;
            border-radius: 999px;
            border: 1px solid var(--brass-700);
            background: var(--brass-500);
            color: var(--green-950);
            cursor: pointer;
            text-decoration: none;
        }

        .toolbar a { background: transparent; color: var(--parch-100); }

        @media print {
            html { background: none; }
            .toolbar { display: none; }
            .sheet, .sheet + .sheet { margin: 0; break-after: page; }
            .sheet:last-child { break-after: auto; }
        }
    </style>
</head>
<body>

<div class="toolbar">
    <button type="button" onclick="window.print()">Print / Save as PDF</button>
    <a href="{{ route('character.show', $character->slug) }}">Back to {{ $character->name }}</a>
</div>

@php
    $cornerSvg = '<svg class="corner corner-%s" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.6">'
        .'<path d="M2 38V10L10 2h28"/><path d="M7 38V13l7-7h24" opacity=".55"/>'
        .'<path d="M14 24c0-5 4-9 9-9s9 4 9 9" opacity=".7"/><circle cx="23" cy="24" r="2.4"/></svg>';
@endphp

{{-- ============================ PAGE ONE ============================ --}}
<div class="sheet">
    <div class="frame">
        {!! sprintf($cornerSvg, 'tl') !!}{!! sprintf($cornerSvg, 'tr') !!}
        {!! sprintf($cornerSvg, 'bl') !!}{!! sprintf($cornerSvg, 'br') !!}

        <div class="masthead">
            <div>
                <div class="eyebrow eyebrow-on-dark">1920s Era Investigator</div>
                <h1>{{ $character->name }}</h1>
                <div class="occupation">{{ $character->occupation ?: 'Occupation unrecorded' }}</div>
                <div style="font-size:6.8pt; color: var(--green-200); margin-top:1.4mm; letter-spacing:0.04em;">
                    Played by {{ $character->player?->name ?? '—' }}
                    &nbsp;·&nbsp; {{ config('app.name') }}
                </div>
            </div>
            @if ($character->avatar)
                <img class="portrait" src="{{ $portrait ?? asset('storage/'.$character->avatar) }}" alt="">
            @else
                <div class="portrait portrait-empty">Portrait</div>
            @endif
        </div>

        <div class="identity">
            @foreach ([
                'Age'        => $character->age,
                'Gender'     => $character->gender,
                'Birthplace' => $character->birthplace,
                'Residence'  => $character->residence,
                'Cred. Rating' => $character->creditRating().'%',
            ] as $label => $value)
                <div class="field">
                    <div class="eyebrow">{{ $label }}</div>
                    <div class="value {{ filled($value) ? '' : 'blank' }}">{{ filled($value) ? $value : '—' }}</div>
                </div>
            @endforeach
        </div>

        <div>
            <div class="rule"><h2>Characteristics</h2></div>
            <div class="characteristics">
                @foreach ($characteristics as $char)
                    <div class="char">
                        <div class="name">
                            <b>{{ $char['label'] }}</b>
                            @if ($char['sub'])<span>{{ $char['sub'] }}</span>@endif
                        </div>
                        <div class="cells">
                            <div class="cell"><span class="cap">Reg</span><span class="num tabular">{{ $char['value'] }}</span></div>
                            <div class="cell minor"><span class="cap">Half</span><span class="num tabular">{{ $half($char['value']) }}</span></div>
                            <div class="cell minor"><span class="cap">Fifth</span><span class="num tabular">{{ $fifth($char['value']) }}</span></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div>
            <div class="rule"><h2>Vitals &amp; Derived</h2></div>
            <div class="vitals">
                <div class="vital marked">
                    <span class="cap">Hit Points</span>
                    <span class="num tabular">{{ $character->hit_points }}</span>
                    <span class="sub">of {{ $maxHitPoints }} max</span>
                </div>
                <div class="vital marked">
                    <span class="cap">Sanity</span>
                    <span class="num tabular">{{ $character->sanity }}</span>
                    <span class="sub">of {{ $maxSanity }} max</span>
                </div>
                <div class="vital">
                    <span class="cap">Magic Pts</span>
                    <span class="num tabular">{{ $character->magic_points }}</span>
                    <span class="sub">POW ÷ 5</span>
                </div>
                <div class="vital">
                    <span class="cap">Luck</span>
                    <span class="num tabular">{{ $character->luck }}</span>
                    <span class="sub">spendable</span>
                </div>
                <div class="vital">
                    <span class="cap">Move</span>
                    <span class="num tabular">{{ $character->move_rate }}</span>
                    <span class="sub">age adjusted</span>
                </div>
                <div class="vital">
                    <span class="cap">Build</span>
                    <span class="num tabular">{{ CharacterCreation::build($character) }}</span>
                    <span class="sub">STR + SIZ</span>
                </div>
                <div class="vital">
                    <span class="cap">Dodge</span>
                    <span class="num tabular">{{ $dodge }}</span>
                    <span class="sub">{{ $half($dodge) }} / {{ $fifth($dodge) }}</span>
                </div>
                <div class="vital">
                    <span class="cap">Dmg Bonus</span>
                    <span class="num tabular" style="font-size:10.5pt">{{ $character->getDamageBonus() }}</span>
                    <span class="sub">per hit</span>
                </div>
            </div>
        </div>

        <div class="conditions">
            @foreach ([
                'Temporary Insanity'  => $character->temporary_insanity,
                'Indefinite Insanity' => $character->indefinite_insanity,
                'Major Wound'         => $character->major_wound,
                'Unconscious'         => $character->unconscious,
                'Dying'               => $character->dying,
            ] as $label => $flag)
                <div class="condition">
                    <span class="tick {{ $flag ? 'on' : '' }}"></span>{{ $label }}
                </div>
            @endforeach
        </div>

        <div>
            <div class="rule"><h2>Skills</h2></div>
            <div class="skills">
                @foreach ($skillColumns as $column)
                    <div class="skill-col">
                        <div class="skill-head">
                            <span></span><span style="text-align:left">Skill</span><span>Reg</span><span>½</span><span>⅕</span>
                        </div>
                        @foreach ($column as $skill)
                            <div class="skill">
                                <span class="box {{ $skill['experience'] ? 'on' : '' }}"></span>
                                <span class="label">{{ $skill['name'] }} <i>({{ $skill['base'] }}%)</i></span>
                                <span class="reg tabular {{ $skill['value'] > $skill['base'] ? 'trained' : '' }}">{{ $skill['value'] }}</span>
                                <span class="frac tabular">{{ $half($skill['value']) }}</span>
                                <span class="frac tabular">{{ $fifth($skill['value']) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>

        <div class="push">
            <div class="rule"><h2>Combat</h2></div>
            <table class="combat">
                <thead>
                    <tr>
                        <th style="width:34%">Weapon</th>
                        <th style="width:10%">Skill</th>
                        <th style="width:18%">Damage</th>
                        <th style="width:8%">Att.</th>
                        <th style="width:16%">Range</th>
                        <th style="width:7%">Ammo</th>
                        <th style="width:7%">Malf.</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Unarmed (brawl)</td>
                        <td class="n tabular">{{ $brawl }}</td>
                        <td>1D3 + {{ $character->getDamageBonus() }}</td>
                        <td class="n">1</td>
                        <td>Touch</td>
                        <td class="n">—</td>
                        <td class="n">—</td>
                    </tr>
                    @foreach ($character->weapons as $weapon)
                        @php $chance = CharacterSheet::skillValue($character, $weapon->skill); @endphp
                        <tr>
                            <td>{{ $weapon->name }}</td>
                            <td class="n tabular">{{ $chance ?? '—' }}</td>
                            <td>{{ $weapon->damage }}</td>
                            <td class="n">{{ $weapon->uses_per_round }}</td>
                            <td>{{ $weapon->base_range }}</td>
                            <td class="n tabular">{{ $weapon->bullets_in_mag }}</td>
                            <td class="n tabular">{{ $weapon->malfunction }}</td>
                        </tr>
                    @endforeach
                    @for ($i = count($character->weapons); $i < 3; $i++)
                        <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <div class="colophon">
            <span>{{ $character->name }} — sheet one of two</span>
            <span>Printed {{ now()->format('j M Y') }}</span>
        </div>
    </div>
</div>

{{-- ============================ PAGE TWO ============================ --}}
<div class="sheet">
    <div class="frame">
        {!! sprintf($cornerSvg, 'tl') !!}{!! sprintf($cornerSvg, 'tr') !!}
        {!! sprintf($cornerSvg, 'bl') !!}{!! sprintf($cornerSvg, 'br') !!}

        <div class="masthead" style="grid-template-columns: 1fr auto; align-items:center">
            <div>
                <div class="eyebrow eyebrow-on-dark">Backstory &amp; Belongings</div>
                <h1 style="font-size:15pt">{{ $character->name }}</h1>
            </div>
            <div style="text-align:right">
                <div class="eyebrow eyebrow-on-dark">Key Connection</div>
                <div style="font-size:8pt; color: var(--parch-100); max-width:70mm">
                    {{ $backstory['key_connection'] ?? '—' }}
                </div>
            </div>
        </div>

        <div>
            <div class="rule"><h2>My Story</h2></div>
            <div class="ruled">@for ($i = 0; $i < 6; $i++)<span></span>@endfor</div>
        </div>

        <div>
            <div class="rule"><h2>Backstory</h2></div>
            <div class="two-col">
                @foreach ($storyFields as $key => $label)
                    <div class="entry">
                        <div class="label">{{ $label }}</div>
                        @if (filled($backstory[$key] ?? null))
                            <div class="text">{{ $backstory[$key] }}</div>
                        @else
                            <div class="ruled">@for ($i = 0; $i < 3; $i++)<span></span>@endfor</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="two-col">
            <div>
                <div class="rule"><h2>Gear &amp; Possessions</h2></div>
                @if (filled($backstory['gear'] ?? null))
                    <div class="text" style="font-size:7.6pt; white-space:pre-line">{{ $backstory['gear'] }}</div>
                @else
                    <div class="ruled">@for ($i = 0; $i < 10; $i++)<span></span>@endfor</div>
                @endif
            </div>
            <div>
                <div class="rule"><h2>Wealth</h2></div>
                <div class="panel">
                    <div class="wealth-row"><span>Credit Rating</span><b class="tabular">{{ $character->creditRating() }}%</b></div>
                    <div class="wealth-row"><span>Living standard</span><b>{{ $wealth['level'] }}</b></div>
                    <div class="wealth-row"><span>Spending level</span><b class="tabular">{{ $wealth['spending'] }}</b></div>
                    <div class="wealth-row"><span>Cash</span><b class="tabular">{{ $wealth['cash'] }}</b></div>
                    <div class="wealth-row" style="border-bottom:0"><span>Assets</span><b class="tabular">{{ $wealth['assets'] }}</b></div>
                </div>
            </div>
        </div>

        @if ($fellows->isNotEmpty())
            <div>
                <div class="rule"><h2>Fellow Investigators</h2></div>
                <div class="fellows">
                    @foreach ($fellows as $fellow)
                        <div class="fellow">
                            <div class="who">{{ $fellow->name }}</div>
                            <div class="what">{{ $fellow->occupation ?: 'Occupation unknown' }} · {{ $fellow->player?->name ?? 'unplayed' }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="push">
            <div class="rule"><h2>Quick Reference</h2></div>
            <div class="quickref">
                <div>
                    <h3>Levels of success</h3>
                    <table class="success-table">
                        <thead>
                            <tr><th>Fumble</th><th>Fail</th><th>Regular</th><th>Hard</th><th>Extreme</th><th>Critical</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>100 / 96+</td><td>&gt; skill</td><td>≤ skill</td><td>≤ ½</td><td>≤ ⅕</td><td>01</td></tr>
                        </tbody>
                    </table>
                    <ul style="margin-top:1.5mm">
                        <li><b>Pushing a roll</b> — justify the reroll; never in combat or on Sanity.</li>
                        <li><b>Luck</b> — spend point-for-point to improve a roll (not damage, not Luck rolls).</li>
                        <li><b>Bonus / penalty die</b> — roll an extra tens die, keep the better / worse.</li>
                    </ul>
                </div>
                <div>
                    <h3>Wounds, healing &amp; madness</h3>
                    <ul>
                        <li><b>Major wound</b> — losing ½ max HP ({{ (int) ceil($maxHitPoints / 2) }}+) in one attack. CON roll or fall unconscious.</li>
                        <li><b>0 HP</b> — unconscious; with a major wound you are <b>dying</b>.</li>
                        <li><b>First Aid</b> heals 1 HP; <b>Medicine</b> heals 1D3.</li>
                        <li><b>Natural healing</b> — 1 HP per day; weekly roll after a major wound.</li>
                        <li><b>Sanity</b> — lose 5+ in one go, or ⅕ of current Sanity in an hour, and you break.</li>
                        <li><b>Temporary insanity</b> 1D10 rounds · <b>Indefinite</b> 1D10 months.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="colophon">
            <span>{{ $character->name }} — sheet two of two</span>
            <span>{{ config('app.name') }} · Printed {{ now()->format('j M Y') }}</span>
        </div>
    </div>
</div>

</body>
</html>
