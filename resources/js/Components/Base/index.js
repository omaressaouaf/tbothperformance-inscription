import { Head, Link } from "@inertiajs/inertia-vue3";
import * as featherIcons from "@zhuowenli/vue-feather-icons";
const baseComponents = import.meta.glob("./*.vue", { eager: true });

export default (app) => {
    app.component("Head", Head)
        .component("Link", Link);

    for (const [iconName, icon] of Object.entries(featherIcons)) {
        icon.props.size.default = "20";
        app.component(iconName, icon);
    }

    for (const [path, definition] of Object.entries(baseComponents)) {
        const componentName = _.upperFirst(
            _.camelCase(
                path
                    .split("/")
                    .pop()
                    .replace(/\.\w+$/, "")
            )
        );

        app.component(componentName, definition.default);
    }
};
