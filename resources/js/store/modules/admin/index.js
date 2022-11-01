import plans from "@/store/modules/admin/plans";
import courseCategories from "@/store/modules/admin/courseCategories";

const modules = {
    plans,
    courseCategories,
};

export default {
    namespaced: true,
    modules,
};
