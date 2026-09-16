import { describe, expect, it } from 'vitest';
import { parseGear, remainingGear } from '@/Pages/Components/Character/gearTransfer.js';

const catalogue = { equipment: [{ id: 1, name: 'Lantern' }], weapon: [{ id: 2, name: 'Revolver' }] };

describe('backstory gear review', () => {
    it('splits mixed separators and bullets while preserving commas and calibre numbers', () => {
        const rows = parseGear(' Lantern;\r\n• Revolver\n- Coat, wool; ;\r.45 rounds\n1. Lucky stone', catalogue);
        expect(rows.map((row) => row.name)).toEqual(['Lantern', 'Revolver', 'Coat, wool', '.45 rounds', 'Lucky stone']);
        expect(rows[0]).toMatchObject({ type: 'equipment', catalogue_id: 1 });
        expect(rows[1]).toMatchObject({ type: 'weapon', catalogue_id: 2 });
        expect(rows[4]).toMatchObject({ catalogue_id: null, include: true });
    });
    it('suggests weapons in prose without treating ammunition or accessories as weapons', () => {
        const rows = parseGear('service revolver retained from the war; pocketknife; rifle ammunition; pistol holster', catalogue);
        expect(rows.map((row) => row.type)).toEqual(['weapon', 'weapon', 'equipment', 'equipment']);
        expect(rows.every((row) => row.catalogue_id === null)).toBe(true);
    });
    it('matches case insensitively and leaves ambiguous names for review', () => {
        expect(parseGear('REVOLVER', catalogue)[0].catalogue_id).toBe(2);
        expect(parseGear('Lantern', { ...catalogue, weapon: [{ id: 3, name: 'Lantern' }] })[0].catalogue_id).toBeNull();
        expect(parseGear(';\n\r ', catalogue)).toEqual([]);
    });
});

 it('keeps the untransferred part when names are shortened or spelling is corrected', () => {
    expect(remainingGear('Worn tweed suit and travelling coat', 'travelling coat')).toBe('Worn tweed suit');
    expect(remainingGear('Worn tweed suit and travelling coat', 'traveling coat')).toBe('Worn tweed suit');
    expect(remainingGear('Worn tweed suit and travelling coat', 'Worn tweed suit')).toBe('travelling coat');
    expect(remainingGear('travelling coat', 'traveling coat')).toBe('');
    expect(remainingGear('leather satchel', 'field bag')).toBe('leather satchel');
 });
