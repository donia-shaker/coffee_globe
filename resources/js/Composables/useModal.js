import { ref } from "vue";

const showModal = ref(false);
const selectedId = ref(null);
const selectedRoute = ref(null);
const selectedType = ref(null);

export function useModal() {
    const openModal = (route, type = null, id = null) => {
        console.log("useModal");
        selectedId.value = id;
        selectedRoute.value = route;
        selectedType.value = type;
        showModal.value = true;
    };

    const closeModal = () => {
        showModal.value = false;
    };

    return {
        showModal,
        selectedId,
        selectedRoute,
        selectedType,
        openModal,
        closeModal,
    };
}
    