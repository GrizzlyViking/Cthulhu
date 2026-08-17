<?php

namespace App\Misc;

/**
 * The Investigator Handbook's skill descriptions (chapter 5, pp. 95–121),
 * transcribed and keyed by the slug the app uses.
 *
 * Each entry is the book's own definition of the skill, condensed to what a
 * player needs while looking at a sheet: what the skill lets an investigator
 * do, and the rules that govern it at the table. The book's "Pushing examples"
 * and "Sample Consequences of failing a Pushed roll" are left out — they are the
 * Keeper's material, and they are longer than the definitions themselves — as
 * are the era sidebars.
 *
 * Where the app carries a skill the book prints as a specialization (each
 * Fighting and Firearms weapon, the three Science disciplines players have
 * added), the entry is that specialization's paragraph. The generic Fighting,
 * Firearms, Art and Craft, Science, Pilot and Survival entries keep the book's
 * note that the generic skill cannot be purchased.
 *
 * Both {@see \Database\Seeders\SkillSeeder} and the backfill migration read from
 * here, so change a description in this table rather than in the seeder.
 *
 * Skills the players have written themselves are absent on purpose: the book has
 * nothing to say about them, and their descriptions are somebody's own words.
 */
class SkillDescriptions
{
    /**
     * Every description the handbook supplies, by slug.
     *
     * @return array<string, string>
     */
    public static function all(): array
    {
        return [
            'accounting' => 'Grants understanding of accountancy procedures and reveals the financial functioning of a business or person. Inspecting the books, one might detect cheated employees, siphoned-off funds, payment of bribes or blackmail, and whether the financial condition is better or worse than claimed. Looking through old accounts, one could see how money was gained or lost in the past, and to whom and for what payment was made.',

            'anthropology' => 'Enables the user to identify and understand an individual’s way of life through observation. If the skill-user observes another culture from within for a time, or works from accurate records concerning an extinct culture, then simple predictions can be made about that culture’s ways and morals, even though the evidence may be incomplete. Studying the culture for a month or more, the anthropologist begins to understand how it functions and, in combination with Psychology, may predict the actions and beliefs of those being studied.',

            'appraise' => 'Used to estimate the value of a particular item, including the quality, material used, and workmanship. Where relevant, the skill-user could pinpoint the age of the item, assess its historical relevance, and detect forgeries.',

            'archeology' => 'Allows dating and identification of artifacts from past cultures, and the detection of fakes. Ensures expertise in setting up and excavating a dig site. On inspecting a site, the user might deduce the purposes and way of life of those who left the remains — Anthropology might aid in this. Archaeology also helps identify written forms of extinct human languages.',

            'art_crafts' => 'Ability with this skill may enable the making or repair of an item, something typically requiring equipment and time; a higher level of success indicates the item is of high quality or precision. It might also be used to make a duplicate or fake, the difficulty depending on the intricacy of the original. A successful roll might provide information about an item, such as where or when it might have been made, some point of history or technique concerning it, or who might have made it. The generic skill cannot be purchased — choose a specialization such as Acting, Fine Art, Forgery or Photography.',

            'artillery' => 'Assumes some form of military training and experience. The user is experienced in the operation of field weapons in warfare, able to work in a crew or detachment to project munitions beyond the range of personal weapons. Many weapons of this nature are too large for a single person to operate. Various specializations exist depending on the period of the game, including cannon, howitzer, mortar, and rocket launcher. As a combat skill, this cannot be pushed.',

            'charm' => 'Charm takes many forms, including physical attraction, seduction, flattery, or simply warmth of personality. It may be used to compel someone to act in a certain way, but not in a manner completely contrary to that person’s normal behavior. Charm is opposed by the Charm or Psychology skills, and may also be used to haggle the price of an item or service down.',

            'climb' => 'Allows a character to climb trees, walls and other vertical surfaces with or without ropes and climbing gear, and encompasses rappelling. Conditions such as firmness of surface, available handholds, wind, visibility and rain may all affect the difficulty level. One successful roll should carry the whole climb in almost all cases; failing the first roll indicates the climb is perhaps beyond the investigator’s capability, and failing a pushed roll is likely to mean a fall.',

            'credit_rating' => 'A measure of how prosperous and financially confident the investigator appears to be. Money opens doors: if the investigator is attempting to use his or her financial status to achieve a goal then Credit Rating may be appropriate, and it can be used in place of APP to gauge first impressions. It is not so much a skill as a gauge of financial wealth, and is not ticked as other skills are. Each occupation has a starting range, and skill points are spent to reach a rating within it.',

            'cthulhu_mythos' => 'Reflects understanding of the inhuman Cthulhu Mythos. It is not founded on the accumulation of knowledge as academic skills are; rather, it represents the opening and tuning of the human mind to the Mythos, so what is learned from encountering Deep Ones is transferable to other entities. Also referred to as “That which man should not know,” the Cthulhu Mythos is antithetical to human understanding, and exposure to it undermines human sanity. A character’s Sanity may never be higher than 99 minus his or her Cthulhu Mythos skill, and there is no tick-box: points come from encounters that end in insanity and from reading forbidden books.',

            'demolitions' => 'The user is familiar with the safe use of demolitions, including setting and defusing explosive charges, and with military-grade demolitions such as anti-personnel mines and plastique. Mines and similar devices are designed to be easy to set and more difficult to remove or defuse. Given enough time and resources, those proficient may rig charges to demolish a building, clear a blocked tunnel, and repurpose explosive devices.',

            'disguise' => 'To be used whenever you wish to appear to be someone other than whom you are. The user changes posture, costume, and/or voice to enact a disguise, posing as another person or another sort of person. Theatrical makeup may help, as will fake ID. Passing as a specific person in a face-to-face meeting with someone who knows the person being imitated is beyond the scope of this skill, and may well need a combined roll at a higher difficulty with Persuade, Charm, or Fast Talk.',

            'dodge' => 'Allows an investigator to instinctively evade blows, thrown missiles, and so forth. It may be used any number of times in a combat round, but only once per attack, and if an attack can be seen a character can try to dodge it. Bullets cannot be dodged, because they cannot be seen when in motion; the best a character can do is take evasive action that makes them harder to hit. As a combat skill, this cannot be pushed.',

            'drive_auto' => 'Anyone with this skill can drive a car or light truck, make ordinary maneuvers, and cope with ordinary vehicle problems. If the investigator wants to lose a pursuer or tail someone, a Drive roll would be appropriate. Some other cultures might replace this skill with a comparative one: the Inuit might use Drive Dogsled, or a Victorian might use Drive Carriage.',

            'electric_repair' => 'Enables the investigator to repair or reconfigure electrical equipment, such as auto ignitions, electric motors, fuse boxes, and burglar alarms. Fixing a device may require special parts or tools, and jobs in the 1920s may call for this skill and Mechanical Repair in combination. It may also be used with modern explosives such as blasting caps, plastic explosives and mines, which are designed to be easy to deploy; defusing them is far trickier, and raises the difficulty — see Demolitions.',

            'fast_talking' => 'Fast Talk is specifically limited to verbal trickery, deception, and misdirection: bamboozling a bouncer into letting you inside a club, getting someone to sign a form they haven’t read, making a policeman look the other way. It is opposed by Psychology or Fast Talk. After a brief period, usually once the fast talker has left the scene, the target will realize they have been conned — the effect is always temporary, though it lasts longer on a Hard success.',

            'fighting' => 'The Fighting skill denotes a character’s skill in melee combat. The generic skill cannot be purchased: choose specializations appropriate to your investigator’s occupation and history. Martial arts are simply a way of developing a person’s Fighting skill, so decide how the character learned to fight, whether formal military training, martial arts classes, or the hard way in the street. As a combat skill, this cannot be pushed.',

            'fighting-axe' => 'Use this skill for larger wood axes. A small hatchet can be used with basic brawling skill, and a thrown axe uses the Throw skill instead. As a combat skill, this cannot be pushed.',

            'fighting-brawl' => 'Includes all unarmed fighting and basic weapons that anyone could pick up and make use of, such as clubs — up to cricket bats or baseball bats — knives, and many improvised weapons like bottles and chair legs. To determine the damage done with an improvised weapon, the Keeper picks something comparable from the weapons list. As a combat skill, this cannot be pushed.',

            'fighting-chainsaw' => 'The first gasoline-powered, mass-produced chainsaw appeared in 1927, though earlier versions existed. The chainsaw is included as a weapon because of its use in numerous films, but note that the chance of a fumble is doubled, and that the investigator risks death or the loss of a limb should this happen. As a combat skill, this cannot be pushed.',

            'fighting-flail' => 'Nunchaku, morning stars, and similar medieval weapons. As a combat skill, this cannot be pushed.',

            'fighting-garrote' => 'Any length of material used to strangle. It requires the victim to make a Fighting Maneuver to escape, or suffer 1D6 damage per round. As a combat skill, this cannot be pushed.',

            'fighting-spear' => 'Lances and spears. If thrown, use the Throw skill instead. As a combat skill, this cannot be pushed.',

            'fighting-sword' => 'All blades over two feet in length. As a combat skill, this cannot be pushed.',

            'fighting-whip' => 'Bolas and whips. As a combat skill, this cannot be pushed.',

            'firearms-bow' => 'Use of bows and crossbows, ranging from medieval longbows to modern, high-powered compound bows. As a combat skill, this cannot be pushed.',

            'firearms-flamethrower' => 'Weapons projecting a stream of ignited flammable liquid or gas, either carried by the operator or mounted on a vehicle. As a combat skill, this cannot be pushed.',

            'firearms-handgun' => 'Use for all pistol-like firearms when firing discrete shots. For machine pistols such as the MAC-11 or Uzi in modern era games, use the Submachine Gun skill when firing bursts. As a combat skill, this cannot be pushed.',

            'firearms-heavy' => 'Use for grenade launchers, anti-tank rockets, and the like. As a combat skill, this cannot be pushed.',

            'firearms-mg' => 'Weapons firing bursts from bipods, tripods, and mounted weapons. If single shots are fired from a bipod, use the Rifle skill. The differences between assault rifle, submachine gun, and light machine gun are tenuous today. As a combat skill, this cannot be pushed.',

            'firearms-rifle' => 'With this skill any type of rifle — lever-action, bolt-action, or semi-automatic — or scatter-gun can be fired. When an assault rifle fires a single shot, or multiple singles, use this skill. As a combat skill, this cannot be pushed.',

            'firearms-shotgun' => 'The handbook covers shotguns with the Rifle skill. Since the load from a shotgun expands in a spreading pattern, the user’s chance to hit does not decrease with range, but the damage dealt does. As a combat skill, this cannot be pushed.',

            'firearms-smg' => 'Use this skill when firing any machine pistol or submachine gun, and for assault rifles set on burst or full automatic fire. As a combat skill, this cannot be pushed.',

            'first_aid' => 'The user is able to provide emergency medical care: applying a splint to a broken leg, stemming bleeding, treating a burn, resuscitating a drowning victim, dressing and cleaning a wound. It cannot be used to treat diseases, where Medicine is required. To be effective it must be delivered within one hour, in which case it grants 1 hit point, and it can rouse an unconscious person. First Aid, and only First Aid, can save the life of a dying character.',

            'history' => 'Enables an investigator to remember the significance of a country, city, region, or person, as pertinent. A successful roll might be used to help identify tools, techniques, or ideas familiar to ancestors, but little known today.',

            'intimidate' => 'Intimidation can take many forms, including physical force, psychological manipulation, and threats. It is used to frighten or compel a person to act in a certain way, and is opposed by Intimidate or Psychology. Backing it up with a weapon or some other powerful threat or incentive may reduce the difficulty level. Pushing an Intimidate roll means taking things to the limit — days of interrogation, or an ultimatum with a gun to the head.',

            'jump' => 'With success, the investigator may leap up or down vertically, or jump horizontally from a standing or running start. As a guide, a Regular success lets an investigator safely leap down to his or her own height, or clear a gap equal to that height from standing, or twice it with a run-up; an Extreme success might double those distances. If falling from a height, a successful Jump prepares for the fall, reducing the damage by half.',

            'language_other' => 'The user’s chance to understand, speak, read, and write in a language other than his or her own; the exact language must be specified. An individual can know any number of languages, and a single successful roll normally allows comprehension of an entire book. At 5% a language can be identified without a roll, at 10% simple ideas can be communicated, at 30% transactional requests are understood, at 50% the speaker is fluent, and at 75% can pass for a native.',

            'language_own' => 'The tongue the investigator grew up with; the exact language must be specified. It automatically starts equal to the investigator’s EDU characteristic, and thereafter he or she understands, speaks, reads, and writes at that percentage or higher.',

            'law' => 'Represents the chance of knowing pertinent law, precedent, legal maneuvers, or court procedure. The practice of law as a profession can lead to great rewards and political office, but requires intense application over many years, and a high Credit Rating is usually crucial too. When in a foreign country the difficulty should be increased, unless the character has spent many months studying that nation’s legal system.',

            'library_use' => 'Enables an investigator to find a piece of information — a certain book, newspaper, or reference — in a library, collection of documents, or database, assuming the item is there; use of the skill marks several hours of continuous search. It can locate a locked case or rare-book special collection, but Persuade, Fast Talk, Charm, Intimidate, Credit Rating, or special credentials may have to be used to get access to what is in question.',

            'listen' => 'Measures the ability of an investigator to interpret and understand sound, including overheard conversations, mutters behind a closed door, and whispered words in a cafe. The Keeper may use it to determine the course of an impending encounter: was your investigator awakened by that cracking twig? By extension, a high Listen skill indicates a good level of general awareness in a character.',

            'locksmith' => 'A locksmith can open car doors, hot-wire autos, jimmy library windows, figure out Chinese puzzle boxes, and penetrate ordinary commercial alarm systems. The user may repair locks, make keys, or open locks with the aid of skeleton keys, picks, and other tools. Especially difficult locks may require a higher difficulty level.',

            'mech_repair' => 'Allows the investigator to repair a broken machine or to create a new one. Basic carpentry and plumbing projects can be performed, as well as constructing items such as a pulley system and repairing items such as a steam pump; special tools or parts may be required. It opens common household locks but nothing more advanced — see Locksmith. Mechanical Repair is a companion skill to Electrical Repair, and both may be necessary to fix complex devices such as a car or an aircraft.',

            'medicine' => 'The user diagnoses and treats accidents, injuries, diseases and poisonings, and makes public health recommendations; where an era has no good treatment for a malady the effort is limited, uncertain, or inconclusive. The skill grants knowledge of a wide variety of drugs and potions, natural and man-made, and of their side effects and contraindications. Treatment takes a minimum of one hour and recovers 1D3 hit points, and a dying character must first be stabilized with First Aid.',

            'natural_world' => 'Originally the study of plant and animal life in its environment. As a skill, Natural World represents the traditional, unscientific knowledge and personal observation of farmers, fishermen, inspired amateurs, and hobbyists: identifying species, habits and habitats in a general way, and tracks, spoors and calls. It may or may not be accurate — this is the region of appreciation, judgment, folk tradition, and enthusiasm. For a scientific understanding of the natural world, look to Biology, Botany and Zoology.',

            'navigate' => 'Allows the user to find his or her way in storms or clear weather, in day or at night. Those of higher skill are familiar with astronomical tables, charts, instruments, and satellite location gear as they exist in the era of play. The skill also covers measuring and mapping an area, whether an island of many square miles or the interior of a single room. If the character is familiar with the area, a bonus die should be granted to the roll.',

            'occult' => 'The user recognizes occult paraphernalia, words, and concepts, as well as folk traditions, and can identify grimoires of magic and occult codes. The occultist is familiar with the families of secret knowledge passed down from Egypt and Sumer, from the Medieval and Renaissance West, and perhaps from Asia and Africa as well. This skill does not apply to the spells, books, and magic of the Cthulhu Mythos, although occult ideas are often adopted by worshipers of the Great Old Ones.',

            'op_hv_machine' => 'Required to drive and operate a tank, backhoe, steam shovel, or other large-scale construction machine. For very different sorts of machines the Keeper may raise the difficulty level if the problems encountered are mostly unfamiliar ones: someone used to running a bulldozer, for instance, will not be quickly competent with the steam turbines in a ship’s engine room.',

            'persuade' => 'Use Persuade to convince a target about a particular idea, concept, or belief through reasoned argument, debate, and discussion; it may be employed without reference to truth. Success takes time, at least half an hour — to persuade someone quickly, use Fast Talk. Given enough time the effect may linger indefinitely and insidiously, for years perhaps, until events or another Persuade turn the target’s mind in another direction. It may also be used to haggle a price down.',

            'pilot' => 'The air or water equivalent of Drive Auto, this is the maneuver skill for flying or floating craft. The generic skill cannot be purchased, and each specialization — Pilot (Aircraft), Pilot (Boat), and so on — starts at 01%. Anyone with modest skill can sail or fly on a calm day with good visibility, although rolls are required for storms, navigation by instrument, low visibility, and other difficult situations; bad weather, poor visibility and damage may raise the difficulty.',

            'psychoanalysis' => 'Refers to the range of emotional therapies, not just Freudian procedures. The common term in the 1920s for an analyst or scholar of emotional disorders was “alienist”; in the present day the skill could justly be named Psychiatric Treatment. Intensive psychoanalysis can return Sanity points to an investigator patient: once per game month a successful roll gains the patient 1D3 Sanity, a failure adds none, and a fumble loses 1D6 and ends that analyst’s treatment. Successful use can also let a character cope with a phobia or mania for a short time, or see delusions for what they are.',

            'psychology' => 'A perception skill common to all humans, it allows the user to study an individual and form an idea of another person’s motives and character. The Keeper may choose to make concealed Psychology rolls on the player’s behalf, announcing only the information, true or false, that the user gained by employing it.',

            'ride' => 'Applies to saddle horses, donkeys, and mules, granting knowledge of basic care of the riding animal, riding gear, and how to handle the steed at a gallop or on difficult terrain. Should a steed unexpectedly rear or stumble, the rider’s chance of remaining mounted equals his or her Ride skill. Riding sidesaddle increases the difficulty by one level, as does an unfamiliar mount such as a camel. A rider who falls loses at least 1D6 hit points, although a Jump roll can negate this.',

            'science_skill' => 'Practical and theoretical ability with a science specialty, which would suggest some degree of formalized education and training, although a well-read amateur scientist is also a possibility; understanding and scope is limited by the era of play. The generic skill cannot be purchased — choose a specialization such as Astronomy, Biology, Botany, Chemistry, Cryptography, Forensics, Geology, Mathematics, Meteorology, Pharmacy, Physics or Zoology. Many specialties cross and overlap, and where a character lacks the obvious discipline they may roll against an allied one at a higher difficulty.',

            'sleight_of_hand' => 'Allows the visual covering-up, secreting, or masking of an object or objects, perhaps with debris, cloth, or other intervening or illusion-promoting materials, perhaps by using a secret panel or false compartment. Larger objects of any sort should be increasingly hard to conceal. Sleight of Hand includes pick-pocketing, palming a card, and clandestine use of a cell phone.',

            'spot-hidden' => 'Allows the user to spot a secret door or compartment, notice a hidden intruder, find an inconspicuous clue, recognize a repainted automobile, become aware of ambushers, notice a bulging pocket, or anything similar. This is an important skill in the armory of an investigator. When searching for a character who is hiding, the opponent’s Stealth skill sets the difficulty level for the roll.',

            'stealth' => 'The art of moving quietly and hiding without alerting those who might hear or see. Ability with the skill suggests either that the character is adept at moving quietly, light-footed, or skilled in camouflage techniques. It might also suggest that the character can maintain a level of patience and cool-headedness to remain still and unseen for long periods.',

            'survival' => 'Provides the expertise required to survive in extreme environments, such as desert or arctic conditions, as well as upon the sea or in wilderness terrain. Inherent is the knowledge of hunting, building shelters, and hazards such as poisonous plants, according to the given environment. The generic skill cannot be purchased: an environment should be chosen when it is taken — Survival (Desert), (Sea), (Arctic) — and where a character lacks the obvious specialty they may roll against an allied one at a higher difficulty.',

            'swim' => 'The ability to float and to move through water or other liquid. Only roll Swim in times of crisis or danger, or when the Keeper judges it appropriate. Failing a pushed Swim roll can result in loss of hit points, and may also lead to the person being washed away downstream, partially or completely drowned.',

            'throw' => 'Use Throw to hit a target with an object. A palm-sized object can be hurled up to STR divided by 5 in yards, and the effective range can be extended to STR divided by 2, though with a penalty die on the roll. If the roll fails, the object lands at a random distance from the target. Throw is used in combat when throwing rocks, spears, grenades, or boomerangs.',

            'track' => 'With Track, an investigator can follow a person, vehicle, or animal over earth, and through plants and leaves. Factors such as the time passed since the tracks were made, rain, and the type of ground covered may affect the difficulty level.',

            /*
             * Science specializations the players have added to the shared list.
             * The book prints these under Science (pp. 116–118), so they get its
             * words like anything else it covers.
             */
            'biology' => 'A Science specialization. The study of life and living organisms, including cytology, ecology, genetics, histology, microbiology and physiology. With this skill one might develop a vaccine against some hideous Mythos bacterium, isolate the hallucinogenic properties of some jungle plant, or perform analysis of blood and organic matter.',

            'pharmacy' => 'A Science specialization. The study of chemical compounds and their effect on living organisms. Traditionally this has involved the formulation, creation, and dispensing of medications, whether a witch-doctor using a combination of herbs or a modern pharmacist operating in a laboratory. The skill ensures the safe and effective use of pharmaceutical drugs, including synthesizing ingredients, identification of toxins, and knowledge of possible side effects.',

            'science-geology' => 'A Science specialization. Used to determine the approximate age of rock strata, recognize fossil types, distinguish minerals and crystals, locate promising sites for drilling or mining, evaluate soils, and anticipate volcanism, seismic events, avalanches, and other such phenomena.',
        ];
    }

    /**
     * The handbook's description of one skill, or null where it has nothing to
     * say about it — a skill a player wrote themselves, for instance.
     */
    public static function for(string $slug): ?string
    {
        return static::all()[$slug] ?? null;
    }
}
