import plans from "@/store/modules/admin/plans";
import courseCategories from "@/store/modules/admin/courseCategories";
import notifications from "@/store/modules/admin/notifications";

const modules = {
    plans,
    courseCategories,
    notifications,
};

export default {
    namespaced: true,
    modules,
};
