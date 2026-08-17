/*
 * Money, as the sheet writes it.
 *
 * The figures themselves are the server's — `App\Misc\Money` reads the
 * handbook's price cells and `Character::wealth` decides what an investigator
 * has. Nothing is parsed here; this only prints what arrives.
 */

/**
 * Cents only when there are cents, so a revolver costs $25 rather than $25.00
 * and a nickel still costs $0.05. Mirrors `App\Misc\Money::format`.
 */
export function formatMoney(value) {
    const amount = Number(value) || 0;

    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: amount % 1 === 0 ? 0 : 2,
        maximumFractionDigits: 2,
    }).format(amount);
}

/** The two purses, plus the honest third option. Mirrors `App\Enums\Purse`. */
export const PURSES = [
    { value: 'cash', label: 'Cash' },
    { value: 'assets', label: 'Assets' },
    { value: 'nothing', label: 'Nothing — it cost me nothing' },
];
