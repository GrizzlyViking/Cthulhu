<script setup>
import {
    ChevronLeftIcon,
    ChevronRightIcon,
    EllipsisHorizontalIcon,
    CheckIcon,
} from '@heroicons/vue/20/solid'
import {onMounted, ref} from "vue";
import {router, useForm, usePage} from "@inertiajs/vue3";
import ModalPopup from "@/Pages/Components/ModalPopup.vue";

const page = usePage();

const prop = defineProps({calendar: Object});
const today = new Date();

const selectedDays = useForm({
    days: [],
    user_id: '',
    summary: '',
    type: 'default',
    description: '',
});

let selected = {
    month: today.toLocaleString('default', { month: 'long' }),
    year: today.getFullYear(),
    day: '2024-06-01'
}

const makeSuggestion = () => {
    selectedDays.summary = 'Suggestion';
    selectedDays.type = 'suggestion';
    selectedDays.description = 'This is a date, suggested by ' + page.props.auth.user.name;
    openModal();
}

const placeAvailability = () => {
    selectedDays.summary = page.props.auth.user.name;
    selectedDays.type = 'vote';
    openModal();
}

let days = ref();
let isAddEventModalOpen = ref(false);

const closeModal = () => {
    isAddEventModalOpen.value = false;
}

const openModal = () => {
    isAddEventModalOpen.value = true;
}

const minusOneMonth = () => {
    let referenceDate = days.value.find((date) => date.isCurrentMonth).date;
    const dateObj = new Date(referenceDate);
    let month = new Date(dateObj.setMonth(dateObj.getMonth() - 1))
    selected.month = month.toLocaleString('default', { month: 'long' });
    selected.year = month.getFullYear();
    refresh_calendar();
}

const plusOneMonth = () => {
    let referenceDate = days.value.find((date) => date.isCurrentMonth).date;
    const dateObj = new Date(referenceDate);
    let month = new Date(dateObj.setMonth(dateObj.getMonth() + 1))
    selected.month = month.toLocaleString('default', { month: 'long' });
    selected.year = month.getFullYear();
    refresh_calendar();
}

const refresh_calendar = () => {
    axios.get(route('calendar.month', {
        calendar: prop.calendar.slug,
        month: selected.month,
        year: selected.year
    })).then((response) => {
        days.value = response.data.calendar;
    });
}

onMounted(() => {
    refresh_calendar();
});

const selectDay = (day) => {
    if (selectedDays.days.indexOf(day) > -1) {
        day.isSelected = false;
        selectedDays.days.splice(selectedDays.days.indexOf(day), 1)
    } else {
        day.isSelected = true;
        selectedDays.days.push(day)
    }
}

const removeAllPlanning = () => {
    router.delete(route('planning.events.delete', prop.calendar.slug))
}

const createEvent = () => {
    selectedDays.user_id = page.props.auth.user.id;
    selectedDays.post(route('events.create', {
        calendar: prop.calendar.slug,
    }), {
        onSuccess: () => {
            refresh_calendar();
            closeModal();
            selectedDays.reset();
        },
        onError: (response) => {
            console.log(response.calendar_id);
        }
    });
}
</script>

<template>
    <div class="lg:flex lg:h-full lg:flex-col">
        <header class="flex items-center justify-between border-b border-gray-200 px-6 py-4 lg:flex-none">
            <h1 class="text-base font-semibold leading-6 text-gray-900">
                <time datetime="2024-06">{{ selected.month }} {{ selected.year }}</time>
            </h1>
            <div class="flex items-center">
                <div class="relative flex items-center rounded-md bg-white shadow-sm md:items-stretch">
                    <button type="button" @click="minusOneMonth"
                            class="flex h-9 w-12 items-center justify-center rounded-l-md border-y border-l border-gray-300 pr-1 text-gray-400 hover:text-gray-500 focus:relative md:w-9 md:pr-0 md:hover:bg-gray-50">
                        <span class="sr-only">Previous month</span>
                        <ChevronLeftIcon class="h-5 w-5" aria-hidden="true"/>
                    </button>
                    <button type="button"
                            class="hidden border-y border-gray-300 px-3.5 text-sm font-semibold text-gray-900 hover:bg-gray-50 focus:relative md:block">
                        Today
                    </button>
                    <span class="relative -mx-px h-5 w-px bg-gray-300 md:hidden"/>
                    <button type="button" @click="plusOneMonth"
                            class="flex h-9 w-12 items-center justify-center rounded-r-md border-y border-r border-gray-300 pl-1 text-gray-400 hover:text-gray-500 focus:relative md:w-9 md:pl-0 md:hover:bg-gray-50">
                        <span class="sr-only">Next month</span>
                        <ChevronRightIcon class="h-5 w-5" aria-hidden="true"/>
                    </button>
                </div>
                <div class="hidden md:ml-4 md:flex md:items-center">
                    <div class="ml-6 h-6 w-px bg-gray-300"/>
                    <button type="button"
                            @click="makeSuggestion"
                            :disabled="selectedDays.days.length === 0"
                            class="ml-6 rounded-md bg-cthulhu-green-400 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-cthulhu-green-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-cthulhu-green-800 disabled:bg-cthulhu-green-200 disabled:cursor-not-allowed disabled:border-cthulhu-green-50 disabled:border">
                        Propose days
                    </button>
                    <button type="button"
                            @click="placeAvailability"
                            :disabled="selectedDays.days.length === 0"
                            class="ml-6 rounded-md bg-cthulhu-green-400 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-cthulhu-green-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-cthulhu-green-800 disabled:bg-cthulhu-green-200 disabled:cursor-not-allowed disabled:border-cthulhu-green-50 disabled:border">
                        Add you're availability
                    </button>
                    <button type="button"
                            @click="openModal"
                            :disabled="selectedDays.days.length === 0"
                            class="ml-6 rounded-md bg-cthulhu-green-400 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-cthulhu-green-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-cthulhu-green-800 disabled:bg-cthulhu-green-200 disabled:cursor-not-allowed disabled:border-cthulhu-green-50 disabled:border">
                        Add event
                    </button>
                    <button type="button"
                            @click="removeAllPlanning"
                            class="ml-6 rounded-md bg-cthulhu-blood-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-cthulhu-green-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-cthulhu-green-800 disabled:bg-cthulhu-green-200 disabled:cursor-not-allowed disabled:border-cthulhu-green-50 disabled:border">
                        Delete <span class="font-bold capitalize">all</span> planning
                    </button>
                </div>
            </div>
        </header>
        <div class="shadow ring-1 ring-black ring-opacity-5 lg:flex lg:flex-auto lg:flex-col">
            <div
                class="grid grid-cols-7 gap-px border-b border-gray-300 bg-gray-200 text-center text-xs font-semibold leading-6 text-gray-700 lg:flex-none">
                <div class="bg-white py-2">M<span class="sr-only sm:not-sr-only">on</span></div>
                <div class="bg-white py-2">T<span class="sr-only sm:not-sr-only">ue</span></div>
                <div class="bg-white py-2">W<span class="sr-only sm:not-sr-only">ed</span></div>
                <div class="bg-white py-2">T<span class="sr-only sm:not-sr-only">hu</span></div>
                <div class="bg-white py-2">F<span class="sr-only sm:not-sr-only">ri</span></div>
                <div class="bg-white py-2">S<span class="sr-only sm:not-sr-only">at</span></div>
                <div class="bg-white py-2">S<span class="sr-only sm:not-sr-only">un</span></div>
            </div>
            <div class="flex bg-gray-200 text-xs leading-6 text-gray-700 lg:flex-auto">
                <div class="hidden w-full lg:grid lg:grid-cols-7 lg:grid-rows-6 lg:gap-px">
                    <div
                        v-for="day in days"
                        :key="day.date"
                        :class="[day.isCurrentMonth ?
                        (day.events.some((day_event) => day_event.type === 'suggestion' ) ? 'bg-cthulhu-yellow-400' : 'bg-white')
                        : 'bg-gray-50 text-gray-500', 'relative px-3 py-2']"
                        @click="selectDay(day)"
                    >
                        <time :datetime="day.start_at"
                              :class="day.isToday ? 'flex h-6 w-6 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white' : undefined">
                            {{ day.date.split('-').pop().replace(/^0/, '') }}
                        </time>

                        <CheckIcon v-if="day.isSelected" class="float-right -mr-1 h-5 w-5 text-gray-400" aria-hidden="true"/>

                        <ol v-if="day.events.filter((event) => event.type !== 'suggestion').length > 0" class="mt-2">
                            <li v-for="event in day.events.filter((event_date) => event_date.type !== 'suggestion').slice(0, 2)" :key="event.id" :class="[event.type === 'vote' && 'bg-cthulhu-green-200 border-1 mb-1 border-cthulhu-green-400 rounded-lg px-2']">
                                <a href="#" class="group flex">
                                    <p class="flex-auto truncate font-medium text-gray-900 group-hover:text-indigo-600">
                                        {{ event.summary }}
                                    </p>
                                    <time :datetime="event.start_at"
                                          class="ml-3 hidden flex-none text-gray-500 group-hover:text-indigo-600 xl:block">
                                        {{ event.time }}
                                    </time>
                                </a>
                            </li>
                            <li v-if="day.events.filter((event) => event.type !== 'suggestion').length > 2" class="text-gray-500">+ {{ day.events.length - 2 }} more
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="isolate grid w-full grid-cols-7 grid-rows-6 gap-px lg:hidden">
                    <button v-for="day in days" :key="day.date" type="button"
                            :class="[day.isCurrentMonth ? 'bg-white' : 'bg-gray-50', (day.isSelected || day.isToday) && 'font-semibold', day.isSelected && 'text-white', !day.isSelected && day.isToday && 'text-indigo-600', !day.isSelected && day.isCurrentMonth && !day.isToday && 'text-gray-900', !day.isSelected && !day.isCurrentMonth && !day.isToday && 'text-gray-500', 'flex h-14 flex-col px-3 py-2 hover:bg-gray-100 focus:z-10']">
                        <time :datetime="day.date"
                              :class="[day.isSelected && 'flex h-6 w-6 items-center justify-center rounded-full', day.isSelected && day.isToday && 'bg-indigo-600', day.isSelected && !day.isToday && 'bg-gray-900', 'ml-auto']">
                            {{ day.date.split('-').pop().replace(/^0/, '') }}
                        </time>
                        <span class="sr-only">{{ day.events.length }} events</span>
                        <span v-if="day.events.length > 0" class="-mx-0.5 mt-auto flex flex-wrap-reverse">
                            <span v-for="event in day.events" :key="event.id"
                                  class="mx-0.5 mb-1 h-1.5 w-1.5 rounded-full bg-gray-400"/>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <modal-popup :is-open="isAddEventModalOpen"
                 @modal-close="closeModal"
                 @response1="closeModal"
                 @response2="createEvent"
    >
        <template #title>Add Events</template>
        <template #default>
            <form>
                <div>
                    <label for="summary" class="block text-sm font-medium leading-6 text-gray-900">
                        Short summary
                    </label>
                    <div class="mt-2">
                        <input type="text" v-model="selectedDays.summary"
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                        <div v-if="page.props.errors.summary" v-text="page.props.errors.summary" class="text-red-500 text-xs mt-1"></div>
                    </div>
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900">
                        Description
                    </label>
                    <div class="mt-2">
                        <input type="text" v-model="selectedDays.description"
                               class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                        <div v-if="page.props.errors.description" v-text="page.props.errors.description" class="text-red-500 text-xs mt-1"></div>
                    </div>
                </div>
                <div v-if="page.props.errors" class="text-red-900">
                    <ul>
                        <li class="flex items-center justify-between">Error: </li>
                    </ul>
                </div>
            </form>
        </template>
        <template #response1>Cancel</template>
        <template #response2>Events on all checked dates</template>
    </modal-popup>
</template>
