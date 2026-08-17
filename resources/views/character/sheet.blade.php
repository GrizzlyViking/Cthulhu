@php
    use App\Misc\CharacterCreation;
    use App\Misc\CharacterSheet;
    use App\Misc\Money;

    $half   = fn (?int $value) => CharacterCreation::half((int) $value);
    $fifth  = fn (?int $value) => CharacterCreation::fifth((int) $value);
    $backstory = $character->backstory ?? [];

    $maxHitPoints = CharacterCreation::hitPoints($character);
    $maxSanity    = CharacterSheet::maxSanity($character);
    $dodge        = CharacterSheet::dodge($character);
    $brawl        = CharacterSheet::skillValue($character, 'fighting-brawl') ?? 25;
    $era          = $character->era();
    $damageBonus  = $character->getDamageBonus();

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

    // How many things are owned decides how narrow the belongings columns get.
    // One column reads best; three is what it takes to fit a hoarder on a page.
    $ownedCount = collect($possessions)->sum(fn (array $bucket) => count($bucket['items']));
    $ownedColumns = match (true) {
        $ownedCount > 44 => 3,
        $ownedCount > 8  => 2,
        default          => 1,
    };
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
           The printed investigator sheet — four A4 pages.

           Deliberately hand-written CSS rather than the app's Tailwind build:
           the sheet is laid out in millimetres against a fixed page, which is a
           different problem from the phone-first utility classes. Palette and
           typography still come from the app's design system.

           Two rules the screen does not have to keep:

           1. **It has to survive a greyscale printer.** Colour is welcome — half
              the table prints in colour — but nothing may depend on it. Brass is
              a light tone: it is used for hairlines, small caps and outlines,
              never as a fill behind a figure that has to be read. Anything that
              needs to stand out is filled dark green with pale text, and
              anything ticked carries a glyph as well as a colour.

           2. **The bottom of a page is not allowed to trail off.** Each page
              ends in a ruled block that grows into whatever room is left, so a
              sparse sheet prints as somewhere to write rather than as a gap.
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

        .muted { color: var(--green-500); }

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

        /* A note pinned to the right of a heading, in the same small caps. */
        .rule .aside {
            font-size: 6pt;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--green-500);
            white-space: nowrap;
            order: 3;
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

        .masthead .byline {
            font-size: 6.8pt;
            color: var(--green-200);
            margin-top: 1.4mm;
            letter-spacing: 0.04em;
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

        /* The slimmer masthead the later pages carry. */
        .masthead.running { grid-template-columns: 1fr auto; align-items: center; }
        .masthead.running h1 { font-size: 15pt; margin: 0.4mm 0 0; }

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

        /* --- Vitals, and the wealth strip that borrows its look ---------- */
        .strip { display: grid; gap: 1.8mm; }
        .strip.of-8 { grid-template-columns: repeat(8, 1fr); }
        .strip.of-4 { grid-template-columns: repeat(4, 1fr); }

        .vital {
            border: 0.3mm solid var(--parch-300);
            border-radius: 1mm;
            background: var(--parch-100);
            text-align: center;
            padding: 1.2mm 0.5mm 1.4mm;
        }

        /* Marked is drawn, not tinted: a brass rule under a heavier border
           survives greyscale where a pale wash would not. */
        .vital.marked { border-color: var(--brass-700); border-width: 0.5mm; background: var(--parch-50); }
        .vital .cap { font-size: 5.2pt; letter-spacing: 0.07em; text-transform: uppercase; color: var(--green-500); display: block; }
        .vital .num { font-size: 13pt; font-weight: 700; line-height: 1.15; }
        .vital .num.money { font-size: 11pt; }
        .vital .num.tight { font-size: 10pt; }
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

        /* A condition that is set carries the cross as well as the colour, so
           it still reads as set out of a black and white printer. */
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
        /* The columns take the whole page and the rows spread inside them, so
           the skill list reads as one solid table rather than a block of type
           with a hole under it. */
        .skills { flex: 1 1 auto; display: grid; grid-template-columns: repeat(3, 1fr); gap: 0 3mm; align-content: stretch; }

        /* Rows spread to fill the column, so the block reads as one solid table. */
        .skill-col { display: flex; flex-direction: column; justify-content: space-between; }

        .skill {
            display: grid;
            grid-template-columns: 3.4mm 1fr 7.4mm 5.6mm 5.6mm;
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
            position: relative;
        }

        /* Flagged for an experience check: dark, and ticked. */
        .skill .box.on { background: var(--green-800); border-color: var(--green-800); }

        .skill .box.on::after {
            content: '✓';
            position: absolute;
            inset: 0;
            color: var(--parch-50);
            font-size: 6pt;
            line-height: 3mm;
            text-align: center;
        }

        .skill .label { font-size: 7.4pt; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .skill .label i { font-style: normal; color: var(--green-500); font-size: 6pt; }

        .skill .reg {
            text-align: center;
            font-size: 8.2pt;
            font-weight: 700;
            background: var(--parch-200);
            border-radius: 0.4mm;
        }

        /* A skill bought above its base is the one thing on this page worth
           finding at a glance, so it is filled dark rather than tinted brass. */
        .skill .reg.trained { background: var(--green-800); color: var(--parch-50); }

        .skill .frac { text-align: center; font-size: 6.6pt; color: var(--green-500); }

        .skill-head {
            display: grid;
            grid-template-columns: 3.4mm 1fr 7.4mm 5.6mm 5.6mm;
            gap: 0.8mm;
            font-size: 5pt;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--green-500);
            text-align: center;
            padding-bottom: 0.4mm;
        }

        /* --- Tables -------------------------------------------------------- */
        table { width: 100%; border-collapse: collapse; }

        .grid-table th {
            font-size: 5.4pt;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--brass-500);
            background: var(--green-900);
            padding: 1.1mm 1.6mm;
            text-align: left;
            font-weight: 600;
        }

        .grid-table td {
            font-size: 7.4pt;
            padding: 0.95mm 1.6mm;
            border-bottom: 0.25mm solid var(--parch-300);
        }

        .grid-table tbody tr:nth-child(even) { background: var(--parch-100); }
        .grid-table td.n { text-align: center; font-weight: 600; }
        .grid-table td.blank { height: 4.6mm; }

        /* --- Belongings ----------------------------------------------------- */
        .belongings { column-gap: 5mm; }
        .belongings .place { break-inside: avoid; margin-bottom: 2.4mm; }
        .belongings .place > p.eyebrow { border-bottom: 0.3mm solid var(--parch-400); padding-bottom: 0.5mm; margin-bottom: 0.8mm; }

        .thing { display: flex; align-items: baseline; gap: 1.2mm; padding: 0.5mm 0; border-bottom: 0.25mm dotted var(--parch-300); }
        .thing .what { flex: 1; font-size: 7.4pt; }
        .thing .what em { font-style: normal; color: var(--green-500); font-size: 6.2pt; }
        .thing .count { font-size: 7pt; font-weight: 700; }

        /* Weapons are marked in the list too — the same thing may be in the
           combat table three pages back. */
        .thing .mark {
            font-size: 5.4pt;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            /* Brass ring, green lettering: the accent survives a colour printer
               and the word survives a grey one. */
            color: var(--green-800);
            border: 0.25mm solid var(--brass-700);
            border-radius: 0.6mm;
            padding: 0 0.6mm;
        }

        /* --- Backstory ------------------------------------------------------ */
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 3mm 5mm; }

        .entry { break-inside: avoid; }
        .entry .label { font-size: 6.2pt; letter-spacing: 0.1em; text-transform: uppercase; color: var(--brass-700); font-weight: 600; }
        .entry .text { font-size: 7.6pt; white-space: pre-line; min-height: 4mm; padding-top: 0.4mm; }

        .panel {
            border: 0.3mm solid var(--parch-300);
            border-radius: 1mm;
            background: var(--parch-100);
            padding: 2.2mm 2.6mm;
        }

        .fellows { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2mm; }

        .fellow { border: 0.3mm solid var(--parch-300); border-radius: 1mm; padding: 1.6mm 2mm; background: var(--parch-100); }
        .fellow .who { font-size: 7.8pt; font-weight: 700; }
        .fellow .what { font-size: 6.4pt; color: var(--green-500); }

        /* --- Quick reference ------------------------------------------------- */
        .quickref { display: grid; grid-template-columns: 1fr 1fr; gap: 3mm 5mm; }
        .quickref h3 { font-size: 6.4pt; letter-spacing: 0.1em; text-transform: uppercase; color: var(--brass-700); margin-bottom: 1mm; }
        .quickref li { font-size: 6.9pt; list-style: none; padding: 0.5mm 0; border-bottom: 0.25mm dotted var(--parch-300); }
        .quickref li b { color: var(--green-800); }

        .success-table th { font-size: 5.4pt; text-transform: uppercase; letter-spacing: 0.05em; background: var(--green-900); color: var(--brass-500); padding: 0.9mm 0.5mm; }
        .success-table td { font-size: 6.8pt; text-align: center; padding: 0.9mm 0.5mm; border-bottom: 0.25mm solid var(--parch-300); }

        /* --- Ruled space ------------------------------------------------------
           The reason no page trails off into nothing. A `.rules` block takes
           whatever height is left over and draws writing lines into it, so the
           leftovers are somewhere to make notes rather than a hole. */
        .rules {
            flex: 1 1 auto;
            min-height: 6mm;
            background-image: repeating-linear-gradient(
                to bottom,
                transparent 0,
                transparent 4.9mm,
                var(--parch-300) 4.9mm,
                var(--parch-300) 5.15mm
            );
        }

        /* Fixed rules, for a block that should not grow: eight lines, say. */
        .rules.fixed { flex: none; }

        .section-fill { display: flex; flex-direction: column; flex: 1 1 auto; min-height: 0; }

        .colophon {
            display: flex;
            justify-content: space-between;
            font-size: 5.6pt;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--green-500);
            padding: 1mm 7mm 0;
        }

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
    $corners = fn () => sprintf($cornerSvg, 'tl').sprintf($cornerSvg, 'tr').sprintf($cornerSvg, 'bl').sprintf($cornerSvg, 'br');
    $pages = 4;
@endphp

{{-- ======================= PAGE ONE — THE INVESTIGATOR ======================= --}}
<div class="sheet">
    <div class="frame">
        {!! $corners() !!}

        <div class="masthead">
            <div>
                <div class="eyebrow eyebrow-on-dark">{{ $era->label() }} Investigator</div>
                <h1>{{ $character->name }}</h1>
                <div class="occupation">{{ $character->occupation ?: 'Occupation unrecorded' }}</div>
                <div class="byline">
                    Played by {{ $character->player?->name ?? '—' }}
                    &nbsp;·&nbsp; {{ $character->currentGame()?->name ?? config('app.name') }}
                </div>
            </div>
            @if ($portrait)
                <img class="portrait" src="{{ $portrait }}" alt="">
            @else
                <div class="portrait portrait-empty">Portrait</div>
            @endif
        </div>

        <div class="identity">
            @foreach ([
                'Age'          => $character->age,
                'Gender'       => $character->gender,
                'Birthplace'   => $character->birthplace,
                'Residence'    => $character->residence,
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
            <div class="strip of-8">
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
                    <span class="num tabular tight">{{ $character->getDamageBonus() }}</span>
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
            <div class="rule">
                <h2>Wealth</h2>
                <span class="aside">{{ $wealth['settled'] ? 'Counted' : 'From Credit Rating' }}</span>
            </div>
            <div class="strip of-4">
                <div class="vital">
                    <span class="cap">Living Standard</span>
                    <span class="num tight">{{ $wealth['living_standard'] }}</span>
                    <span class="sub">Credit Rating {{ $character->creditRating() }}</span>
                </div>
                <div class="vital">
                    <span class="cap">Spending Level</span>
                    <span class="num money tabular">{{ Money::format($wealth['spending_level']) }}</span>
                    <span class="sub">no counting below this</span>
                </div>
                <div class="vital marked">
                    <span class="cap">Cash</span>
                    <span class="num money tabular">{{ Money::format($wealth['cash']) }}</span>
                    <span class="sub">on the person</span>
                </div>
                <div class="vital">
                    <span class="cap">Assets</span>
                    <span class="num money tabular">{{ Money::format($wealth['assets']) }}</span>
                    <span class="sub">property, and slow to reach</span>
                </div>
            </div>
        </div>

        <div class="section-fill">
            <div class="rule"><h2>Combat</h2></div>
            <table class="grid-table">
                <thead>
                    <tr>
                        <th style="width:31%">Weapon</th>
                        <th style="width:9%">Skill</th>
                        <th style="width:17%">Damage</th>
                        <th style="width:7%">Att.</th>
                        <th style="width:15%">Range</th>
                        <th style="width:7%">Mag.</th>
                        <th style="width:7%">Carried</th>
                        <th style="width:7%">Malf.</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Everybody can punch, so the table opens with it — unless
                         the sheet already carries Brawl (Unarmed) as a weapon. --}}
                    @unless (CharacterSheet::carriesUnarmed($character))
                        <tr>
                            <td>Unarmed (brawl)</td>
                            <td class="n tabular">{{ $brawl }}</td>
                            <td>{{ CharacterSheet::damage('1D3+DB', $damageBonus) }}</td>
                            <td class="n">1</td>
                            <td>Touch</td>
                            <td class="n">—</td>
                            <td class="n">—</td>
                            <td class="n">—</td>
                        </tr>
                    @endunless
                    @foreach ($character->weapons as $weapon)
                        @php $chance = CharacterSheet::skillValue($character, $weapon->skill); @endphp
                        <tr>
                            <td>{{ $weapon->name }}</td>
                            <td class="n tabular">{{ $chance ?? '—' }}</td>
                            <td>{{ CharacterSheet::damage($weapon->damage, $damageBonus) }}</td>
                            <td class="n">{{ $weapon->uses_per_round }}</td>
                            <td>{{ $weapon->base_range }}</td>
                            <td class="n tabular">
                                @if ($weapon->magazine_capacity)
                                    {{ $weapon->pivot->ammo }}/{{ $weapon->magazine_capacity }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="n tabular">{{ $weapon->magazine_capacity ? $weapon->pivot->ammo_reserve : '—' }}</td>
                            {{-- The book prints a plain dash where a weapon cannot
                                 jam; the sheet prints a proper one. --}}
                            <td class="n tabular">{{ trim((string) $weapon->malfunction, '- ') === '' ? '—' : $weapon->malfunction }}</td>
                        </tr>
                    @endforeach
                    {{-- Blank rows for whatever gets picked up between prints. --}}
                    @for ($i = count($character->weapons); $i < 5; $i++)
                        <tr><td class="blank">&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                    @endfor
                </tbody>
            </table>

            <div class="rule" style="margin-top:2.2mm"><h2>Wounds, Healing &amp; Madness</h2></div>
            <div class="quickref">
                <ul>
                    <li><b>Major wound</b> — losing ½ max HP ({{ (int) ceil($maxHitPoints / 2) }}+) in one attack. CON roll or fall unconscious.</li>
                    <li><b>0 HP</b> — unconscious; with a major wound you are <b>dying</b>.</li>
                    <li><b>First Aid</b> heals 1 HP; <b>Medicine</b> heals 1D3.</li>
                </ul>
                <ul>
                    <li><b>Natural healing</b> — 1 HP per day; a weekly roll after a major wound.</li>
                    <li><b>Sanity</b> — lose 5+ in one go, or ⅕ of current Sanity in an hour, and you break.</li>
                    <li><b>Temporary insanity</b> 1D10 rounds · <b>Indefinite</b> 1D10 months.</li>
                </ul>
            </div>

            <div class="rule" style="margin-top:2.2mm">
                <h2>Notes</h2>
                <span class="aside">spells, blessings, and what the Keeper said</span>
            </div>
            <div class="rules"></div>
        </div>

        <div class="colophon">
            <span>{{ $character->name }} — sheet one of {{ $pages }}</span>
            <span>Printed {{ now()->format('j M Y') }}</span>
        </div>
    </div>
</div>

{{-- ============================ PAGE TWO — SKILLS ============================ --}}
<div class="sheet">
    <div class="frame">
        {!! $corners() !!}

        <div class="masthead running">
            <div>
                <div class="eyebrow eyebrow-on-dark">Skills</div>
                <h1>{{ $character->name }}</h1>
            </div>
            <div style="text-align:right">
                <div class="eyebrow eyebrow-on-dark">Filled box</div>
                <div style="font-size:8pt; color: var(--parch-100);">Marked for an experience check</div>
            </div>
        </div>

        <div class="section-fill">
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

        <div>
            <div class="rule"><h2>Levels of Success</h2></div>
            <div class="quickref">
                <table class="success-table">
                    <thead>
                        <tr><th>Fumble</th><th>Fail</th><th>Regular</th><th>Hard</th><th>Extreme</th><th>Critical</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>100 / 96+</td><td>&gt; skill</td><td>≤ skill</td><td>≤ ½</td><td>≤ ⅕</td><td>01</td></tr>
                    </tbody>
                </table>
                <ul>
                    <li><b>Pushing a roll</b> — justify the reroll; never in combat or on Sanity.</li>
                    <li><b>Luck</b> — spend point for point to improve a roll, but not damage and not a Luck roll.</li>
                    <li><b>Bonus / penalty die</b> — roll an extra tens die and keep the better, or the worse.</li>
                </ul>
            </div>
        </div>

        <div class="colophon">
            <span>{{ $character->name }} — sheet two of {{ $pages }}</span>
            <span>Printed {{ now()->format('j M Y') }}</span>
        </div>
    </div>
</div>

{{-- ========================= PAGE THREE — BELONGINGS ========================= --}}
<div class="sheet">
    <div class="frame">
        {!! $corners() !!}

        <div class="masthead running">
            <div>
                <div class="eyebrow eyebrow-on-dark">Belongings</div>
                <h1>{{ $character->name }}</h1>
            </div>
            <div style="text-align:right">
                <div class="eyebrow eyebrow-on-dark">Cash in hand</div>
                <div class="tabular" style="font-size:12pt; color: var(--parch-50); font-weight:700;">
                    {{ Money::format($wealth['cash']) }}
                </div>
            </div>
        </div>

        <div>
            <div class="rule">
                <h2>Carried &amp; Kept</h2>
                @if ($ownedCount)
                    <span class="aside">{{ $ownedCount }} {{ Str::plural('item', $ownedCount) }}</span>
                @endif
            </div>

            @if ($possessions === [])
                <p class="muted" style="font-size:7.4pt; padding: 1mm 0 2mm;">
                    Nothing carried and nothing packed. Write in what this investigator picks up.
                </p>
            @else
                <div class="belongings" style="column-count: {{ $ownedColumns }}">
                    @foreach ($possessions as $bucket)
                        <div class="place">
                            <p class="eyebrow">{{ $bucket['location'] }}</p>
                            @foreach ($bucket['items'] as $item)
                                <div class="thing">
                                    <span class="what">
                                        {{ $item['name'] }}
                                        @if ($item['weapon'])<span class="mark">Weapon</span>@endif
                                        @if ($item['detail'] || $item['notes'])
                                            <em>{{ collect([$item['detail'], $item['notes']])->filter()->implode(' · ') }}</em>
                                        @endif
                                    </span>
                                    <span class="count tabular">{{ $item['quantity'] > 1 ? '×'.$item['quantity'] : '' }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        @if (filled($backstory['gear'] ?? null))
            <div>
                <div class="rule"><h2>Gear &amp; Notable Possessions</h2></div>
                <div class="entry"><div class="text">{{ $backstory['gear'] }}</div></div>
            </div>
        @endif

        <div class="section-fill">
            <div class="rule">
                <h2>Acquired at the Table</h2>
                <span class="aside">what it was, where it came from, what it cost</span>
            </div>
            <div class="rules"></div>
        </div>

        <div class="colophon">
            <span>{{ $character->name }} — sheet three of {{ $pages }}</span>
            <span>Printed {{ now()->format('j M Y') }}</span>
        </div>
    </div>
</div>

{{-- ==================== PAGE FOUR — BACKSTORY & THE PARTY ==================== --}}
{{-- The backstory has a page to itself because a well-written one runs long, and
     whatever room it leaves over becomes the journal. --}}
<div class="sheet">
    <div class="frame">
        {!! $corners() !!}

        <div class="masthead running">
            <div>
                <div class="eyebrow eyebrow-on-dark">Backstory</div>
                <h1>{{ $character->name }}</h1>
            </div>
            <div style="text-align:right">
                <div class="eyebrow eyebrow-on-dark">Key Connection</div>
                <div style="font-size:8pt; color: var(--parch-100); max-width:70mm">
                    {{ $backstory['key_connection'] ?? '—' }}
                </div>
            </div>
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
                            <div class="rules fixed" style="height: 10.3mm; margin-top: 0.8mm"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        @if ($fellows->isNotEmpty())
            <div>
                <div class="rule">
                    <h2>Fellow Investigators</h2>
                    <span class="aside">{{ $character->currentGame()?->name ?? 'the party' }}</span>
                </div>
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

        <div class="section-fill">
            <div class="rule">
                <h2>What Happened, and to Whom</h2>
                <span class="aside">dates, names, and what was said</span>
            </div>
            <div class="rules"></div>
        </div>

        <div class="colophon">
            <span>{{ $character->name }} — sheet four of {{ $pages }}</span>
            <span>{{ config('app.name') }} · Printed {{ now()->format('j M Y') }}</span>
        </div>
    </div>
</div>

</body>
</html>
