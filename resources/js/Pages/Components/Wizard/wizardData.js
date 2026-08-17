/**
 * Reference data and pure helpers for the "Create Character" wizard.
 * Content condensed from the Investigator Handbook, chapter 3 (pp. 42-62).
 *
 * Design principle: the player rolls REAL dice at the table. The app never
 * rolls — it only multiplies, derives and keeps the bookkeeping straight.
 */

export const CHARACTERISTICS = [
    {
        key: 'strength',
        abbr: 'STR',
        name: 'Strength',
        dice: '3D6',
        rollHelp: 'Roll 3D6 and enter the total (3–18)',
        min: 3,
        max: 18,
        description:
            'Muscle power: how much you can lift and how hard you hit in hand-to-hand combat. At STR 0 you are an invalid, unable to leave your bed.',
        ladder: [
            [0, 'Enfeebled: unable to even stand up or lift a cup of tea'],
            [15, 'Puny, weak'],
            [50, 'Average human strength'],
            [90, 'One of the strongest people you have ever met'],
            [99, 'World-class (Olympic weightlifter). Human maximum'],
        ],
    },
    {
        key: 'constitution',
        abbr: 'CON',
        name: 'Constitution',
        dice: '3D6',
        rollHelp: 'Roll 3D6 and enter the total (3–18)',
        min: 3,
        max: 18,
        description:
            'Health, vigor and vitality. Poison and disease challenge CON directly, and CON helps determine hit points. At CON 0 the investigator dies.',
        ladder: [
            [0, 'Dead'],
            [1, 'Sickly, prone to prolonged illness'],
            [15, 'Weak health, a great propensity for feeling pain'],
            [50, 'Average healthy human'],
            [90, 'Shrugs off colds, hardy and hale'],
            [99, 'Iron constitution. Human maximum'],
        ],
    },
    {
        key: 'size',
        abbr: 'SIZ',
        name: 'Size',
        dice: '2D6+6',
        rollHelp: 'Roll 2D6, add 6, and enter the total (8–18)',
        min: 8,
        max: 18,
        description:
            'Height and weight averaged into one number. SIZ helps determine hit points, damage bonus and build.',
        ladder: [
            [1, 'A baby (1 to 12 pounds)'],
            [15, 'Child, or very short in stature (33 lbs / 15 kg)'],
            [65, 'Average human size (170 lbs / 75 kg)'],
            [80, 'Very tall, strongly built, or obese (240 lbs / 110 kg)'],
            [99, 'Oversize in some respect (330 lbs / 150 kg)'],
        ],
    },
    {
        key: 'dexterity',
        abbr: 'DEX',
        name: 'Dexterity',
        dice: '3D6',
        rollHelp: 'Roll 3D6 and enter the total (3–18)',
        min: 3,
        max: 18,
        description:
            'Quickness, nimbleness and flexibility. In combat, the character with the highest DEX acts first. At DEX 0 you cannot perform physical tasks.',
        ladder: [
            [0, 'Unable to move without assistance'],
            [15, 'Slow, clumsy with poor motor skills'],
            [50, 'Average human dexterity'],
            [90, 'Fast, nimble: an acrobat or great dancer'],
            [99, 'World-class athlete. Human maximum'],
        ],
    },
    {
        key: 'appearance',
        abbr: 'APP',
        name: 'Appearance',
        dice: '3D6',
        rollHelp: 'Roll 3D6 and enter the total (3–18)',
        min: 3,
        max: 18,
        description:
            'Physical attractiveness and personality combined. High APP is charming and likeable; useful in social encounters and first impressions.',
        ladder: [
            [0, 'So unsightly that others react with fear, revulsion or pity'],
            [15, 'Ugly, possibly disfigured'],
            [50, 'Average human appearance'],
            [90, 'One of the most charming people you could meet'],
            [99, 'The height of glamour and cool. Human maximum'],
        ],
    },
    {
        key: 'intelligence',
        abbr: 'INT',
        name: 'Intelligence',
        dice: '2D6+6',
        rollHelp: 'Roll 2D6, add 6, and enter the total (8–18)',
        min: 8,
        max: 18,
        description:
            'How well you learn, remember, analyse and solve puzzles. INT sets your Personal Interest skill points (INT × 2) and is used for Idea rolls.',
        ladder: [
            [0, 'No intellect, unable to comprehend the world'],
            [15, 'Slow learner, only the most basic math or reading'],
            [50, 'Average human intellect'],
            [90, 'Quick-witted, comprehends multiple languages or theorems'],
            [99, 'Genius (Einstein, Da Vinci, Tesla). Human maximum'],
        ],
    },
    {
        key: 'power',
        abbr: 'POW',
        name: 'Power',
        dice: '3D6',
        rollHelp: 'Roll 3D6 and enter the total (3–18)',
        min: 3,
        max: 18,
        description:
            'Force of will and aptitude for (and resistance to) magic. Starting Sanity equals POW; magic points equal one fifth of POW. POW lost in play is usually gone forever.',
        ladder: [
            [0, 'Enfeebled mind, no willpower or magical potential'],
            [15, 'Weak-willed, easily dominated'],
            [50, 'Average human'],
            [90, 'Strong-willed, a high potential to connect with the unseen'],
            [100, 'Iron will, strong connection to the spiritual realm'],
        ],
    },
    {
        key: 'education',
        abbr: 'EDU',
        name: 'Education',
        dice: '2D6+6',
        rollHelp: 'Roll 2D6, add 6, and enter the total (8–18)',
        min: 8,
        max: 18,
        description:
            'Formal and factual knowledge, and time spent in education. EDU sets your Own Language skill and feeds most occupation skill point formulas.',
        ladder: [
            [0, 'A newborn baby'],
            [15, 'Completely uneducated in every way'],
            [60, 'High school graduate'],
            [70, 'College graduate (Bachelor degree)'],
            [80, 'Degree level graduate (Master’s degree)'],
            [90, 'Doctorate, professor'],
            [96, 'World-class authority in their field'],
            [99, 'Human maximum'],
        ],
    },
];

export const LUCK = {
    key: 'luck',
    abbr: 'LUCK',
    name: 'Luck',
    dice: '3D6',
    rollHelp: 'Roll 3D6 and enter the total (3–18)',
    rollHelpTeen:
        'Age 15–19: roll 3D6 twice and enter BOTH totals — the higher one counts',
    min: 3,
    max: 18,
    description:
        'The fickle hand of fate. The Keeper calls for Luck rolls when circumstances outside your control are in question.',
};

/**
 * Point Buy (Investigator Handbook, "Option 4"): a pool shared among the eight
 * characteristics instead of dice. The minimum for INT and SIZ is a
 * recommendation the Keeper may waive, so it warns rather than blocks.
 */
export const POINT_BUY = {
    pool: 460,
    min: 15,
    max: 90,
    step: 5,
    recommendedMinimum: 40,
    recommendedFor: ['intelligence', 'size'],
};

/**
 * Age modifiers table (Investigator Handbook p.45 & p.48).
 * Modifiers are for the chosen age bracket only — they are not cumulative.
 */
export function ageModifiers(age) {
    const a = Number(age);
    if (!a || a < 15 || a > 90) return null;
    if (a <= 19) {
        return {
            bracket: '15–19',
            summary:
                'Deduct 5 points among STR and SIZ. Deduct 5 points from EDU. Roll for Luck twice and use the higher value.',
            eduChecks: 0,
            eduPenalty: 5,
            appPenalty: 0,
            deduction: { amount: 5, targets: ['strength', 'size'] },
            luckTwice: true,
            moveDeduction: 0,
        };
    }
    if (a <= 39) {
        return {
            bracket: '20s or 30s',
            summary: 'Make 1 improvement check for EDU.',
            eduChecks: 1,
            eduPenalty: 0,
            appPenalty: 0,
            deduction: null,
            luckTwice: false,
            moveDeduction: 0,
        };
    }
    const brackets = {
        4: { label: '40s', checks: 2, deduct: 5, app: 5, move: 1 },
        5: { label: '50s', checks: 3, deduct: 10, app: 10, move: 2 },
        6: { label: '60s', checks: 4, deduct: 20, app: 15, move: 3 },
        7: { label: '70s', checks: 4, deduct: 40, app: 20, move: 4 },
        8: { label: '80s', checks: 4, deduct: 80, app: 25, move: 5 },
    };
    // Ninety is allowed on the profile step and has no bracket of its own, so
    // it stays on the eighties' row rather than falling off the table.
    const b = brackets[Math.min(Math.floor(a / 10), 8)];
    return {
        bracket: b.label,
        summary: `Make ${b.checks} improvement checks for EDU, deduct ${b.deduct} points among STR, CON or DEX, and reduce APP by ${b.app}.`,
        eduChecks: b.checks,
        eduPenalty: 0,
        appPenalty: b.app,
        deduction: { amount: b.deduct, targets: ['strength', 'constitution', 'dexterity'] },
        luckTwice: false,
        moveDeduction: b.move,
    };
}

export const half = (v) => Math.floor((Number(v) || 0) / 2);
export const fifth = (v) => Math.floor((Number(v) || 0) / 5);

/** Table I: Damage Bonus and Build. */
export function damageBonusAndBuild(strength, size) {
    const total = (Number(strength) || 0) + (Number(size) || 0);
    if (total <= 64) return { damageBonus: '-2', build: -2 };
    if (total <= 84) return { damageBonus: '-1', build: -1 };
    if (total <= 124) return { damageBonus: 'None', build: 0 };
    if (total <= 164) return { damageBonus: '+1D4', build: 1 };
    if (total <= 204) return { damageBonus: '+1D6', build: 2 };
    return { damageBonus: '+2D6', build: 3 };
}

/** MOV 7/8/9 rule, before the age deduction. */
export function baseMoveRate(strength, dexterity, size) {
    if (strength < size && dexterity < size) return 7;
    if (strength > size && dexterity > size) return 9;
    return 8;
}

export const hitPoints = (constitution, size) =>
    Math.floor(((Number(constitution) || 0) + (Number(size) || 0)) / 10);

/** Occupation skill point pool for a set of final characteristic values. */
export function occupationPool(occupation, stats) {
    if (!occupation || !Array.isArray(occupation.skill_points_formula)) return 0;
    return occupation.skill_points_formula.reduce((sum, component) => {
        const best = Math.max(
            ...component.options.map((key) => Number(stats?.[key]) || 0)
        );
        return sum + component.multiplier * best;
    }, 0);
}

/* The wizard and the sheet print money the same way; it lives with the sheet's
   other money helpers, and is re-exported here so the steps need not know. */
export { formatMoney } from '@/Pages/Composables/useMoney.js';

export const eraLabel = (era) => (era === 'modern' ? 'Modern Day' : '1920s');

/* ------------------------------------------------------------------ *
 * Backstory: 1D10 inspiration tables (Investigator Handbook pp.52-56) *
 * ------------------------------------------------------------------ */

export const PERSONAL_DESCRIPTION_WORDS = [
    'Rugged', 'Handsome', 'Ungainly', 'Pretty', 'Glamorous', 'Baby-faced',
    'Smart', 'Untidy', 'Dull', 'Dirty', 'Dazzler', 'Bookish', 'Youthful',
    'Weary', 'Plump', 'Stout', 'Hairy', 'Slim', 'Elegant', 'Scruffy',
    'Stocky', 'Pale', 'Sullen', 'Ordinary', 'Rosy', 'Tanned', 'Wrinkled',
    'Stuffy', 'Mousy', 'Sharp', 'Brawny', 'Dainty', 'Muscular', 'Strapping',
    'Gawky', 'Frail',
];

export const IDEOLOGY_TABLE = [
    'There is a higher power that you worship and pray to (e.g. Vishnu, Jesus Christ, Haile Selassie I)',
    'Mankind can do fine without religions (e.g. staunch atheist, humanist, secularist)',
    'Science has all the answers — pick a particular interest (e.g. evolution, cryogenics, space exploration)',
    'A belief in fate (e.g. karma, the class system, superstitious)',
    'Member of a society or secret society (e.g. Freemason, Women’s Institute, Anonymous)',
    'There is evil in society that should be rooted out — what is it? (e.g. drugs, violence, racism)',
    'The occult (e.g. astrology, spiritualism, tarot)',
    'Politics (e.g. conservative, socialist, liberal)',
    '“Money is power, and I’m going to get all I can” (e.g. greedy, enterprising, ruthless)',
    'Campaigner / activist (e.g. feminism, gay rights, union power)',
];

export const SIGNIFICANT_WHO_TABLE = [
    'Parent (e.g. mother, father, stepmother)',
    'Grandparent (e.g. maternal grandmother, paternal grandfather)',
    'Sibling (e.g. brother, half-brother, stepsister)',
    'Child (son or daughter)',
    'Partner (e.g. spouse, fiancé, lover)',
    'The person who taught you your highest occupational skill (e.g. a schoolteacher, your master, your father)',
    'Childhood friend (e.g. classmate, neighbor, imaginary friend)',
    'A famous person — your idol or hero, perhaps never met (e.g. film star, politician, musician)',
    'A fellow investigator in your game — pick one or choose randomly',
    'A non-player character — ask the Keeper to pick one for you',
];

export const SIGNIFICANT_WHY_TABLE = [
    'You are indebted to them. How did they help you? (e.g. financially, protection through hard times, your first job)',
    'They taught you something. What? (e.g. a skill, to love, to be a man)',
    'They give your life meaning. How? (e.g. you aspire to be like them, to be with them, to make them happy)',
    'You wronged them and seek reconciliation. What did you do? (e.g. stole from them, informed on them, refused to help)',
    'Shared experience. What? (e.g. you lived through hard times together, grew up together, served in the war together)',
    'You seek to prove yourself to them. How? (e.g. a good job, a good spouse, an education)',
    'You idolize them (e.g. for their fame, their beauty, their work)',
    'A feeling of regret (e.g. you should have died in their place, you fell out over something you said)',
    'You wish to prove yourself better than them. What was their flaw? (e.g. lazy, drunk, unloving)',
    'They have crossed you and you seek revenge. For what? (e.g. death of a loved one, financial ruin, marital breakup)',
];

export const LOCATIONS_TABLE = [
    'Your seat of learning (e.g. school, university, apprenticeship)',
    'Your hometown (e.g. rural village, market town, busy city)',
    'The place you met your first love (e.g. a music concert, on holiday, a bomb shelter)',
    'A place for quiet contemplation (e.g. the library, country walks, fishing)',
    'A place for socializing (e.g. gentlemen’s club, local bar, uncle’s house)',
    'A place connected with your ideology / belief (e.g. parish church, Mecca, Stonehenge)',
    'The grave of a significant person. Who? (e.g. a parent, a child, a lover)',
    'Your family home (e.g. a country estate, a rented flat, the orphanage you were raised in)',
    'The place you were happiest in your life (e.g. the park bench of your first kiss, your university)',
    'Your workplace (e.g. the office, library, bank)',
];

export const POSSESSIONS_TABLE = [
    'An item connected with your highest skill (e.g. expensive suit, false ID, brass knuckles)',
    'An essential item for your occupation (e.g. doctor’s bag, car, lock picks)',
    'A memento from your childhood (e.g. comics, pocketknife, lucky coin)',
    'A memento of a departed person (e.g. jewelry, a photograph in your wallet, a letter)',
    'Something given to you by your Significant Person (e.g. a ring, a diary, a map)',
    'Your collection. What is it? (e.g. bus tickets, stuffed animals, records)',
    'Something you found but don’t understand — you seek answers (e.g. a letter in an unknown language, a strange silver ball dug up in your garden)',
    'A sporting item (e.g. cricket bat, a signed baseball, a fishing rod)',
    'A weapon (e.g. service revolver, your old hunting rifle, the hidden knife in your boot)',
    'A pet (e.g. a dog, a cat, a tortoise)',
];

export const TRAITS_TABLE = [
    'Generous (e.g. generous tipper, always helps a person in need, philanthropist)',
    'Good with animals (e.g. loves cats, grew up on a farm, good with horses)',
    'Dreamer (e.g. given to flights of fancy, visionary, highly creative)',
    'Hedonist (e.g. life and soul of the party, entertaining drunk, “live fast and die young”)',
    'Gambler and risk-taker (e.g. poker-faced, try anything once, lives on the edge)',
    'Good cook (e.g. bakes wonderful cakes, a meal from almost nothing, refined palate)',
    'Ladies’ man / seductress (e.g. suave, charming voice, enchanting eyes)',
    'Loyal (e.g. stands by their friends, never breaks a promise, would die for their beliefs)',
    'A good reputation (e.g. the best after-dinner speaker in the country, the most pious of men, fearless)',
    'Ambitious (e.g. to achieve a goal, to become the boss, to have it all)',
];

export const KEY_CONNECTION_CATEGORIES = [
    'Personal Description',
    'Ideology / Beliefs',
    'Significant People',
    'Meaningful Locations',
    'Treasured Possessions',
    'Traits',
];
