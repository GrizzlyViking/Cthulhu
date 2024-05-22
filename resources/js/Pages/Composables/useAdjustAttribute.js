import {debounce} from "lodash";
import {usePage} from "@inertiajs/vue3";

export function useAdjustAttribute(characterProp) {
    let character = () => usePage().props[characterProp]

    const updateAttribute =  debounce((skill, event) => {
        axios.put(route('attribute.update', {
            character: character().slug,
            attribute: skill,
            value: event.target.value
        })).then(() => {
                return 'ok';
            }
        );
    }, 600);

    const updateSkill =  debounce((skill, event) => {
        axios.put(route('attribute.update', {
            character: character().slug,
            skill: skill,
            value: event.target.value
        })).then(() => {
                return 'ok';
            }
        );
    }, 600);

    return {
        updateAttribute,
        updateSkill
    }
}

