<template>
    <template
        v-for="(grandparentItem, grandparentItemIndex) in menuItems"
        :key="grandparentItemIndex"
    >
        <MenuDivider v-if="grandparentItem === 'divider'" />
        <Component
            v-else
            :href="grandparentItem.url"
            :title="grandparentItem.title"
            :icon="grandparentItem.icon"
            :collapse-level="1"
            :is="grandparentItem.url ? 'MenuLink' : 'MenuCollapse'"
            :active="itemIsActive(grandparentItem)"
        >
            <Component
                v-for="(
                    parentItem, parentItemIndex
                ) in grandparentItem.children"
                :key="parentItemIndex"
                :anchor="parentItem.anchor"
                :rest-attrs="parentItem.restAttrs"
                :href="parentItem.url"
                :title="parentItem.title"
                :icon="parentItem.icon"
                :collapse-level="2"
                :is="parentItem.url ? 'MenuLink' : 'MenuCollapse'"
                :active="itemIsActive(parentItem)"
            >
                <MenuLink
                    v-for="(childItem, childItemIndex) in parentItem.children"
                    :key="childItemIndex"
                    :href="childItem.url"
                    :title="childItem.title"
                    :icon="childItem.icon"
                    :active="itemIsActive(childItem)"
                    class="ms-2"
                />
            </Component>
        </Component>
    </template>
</template>

<script>
export default {
    inject: ["menuItems"],
    methods: {
        itemIsActive(item) {
            // if the current path ends with '/' we need to slice it away because the route function returns the url without '/' in the end . this way the exact match comparison would work
            const currentPath = this.$page.url.endsWith("/")
                ? this.$page.url.substr(0, this.$page.url.length - 1)
                : this.$page.url;
            const currentUrl = this._baseUrl + currentPath;

            // if the item has url then if it's the dashboard route '/'. we should do an exact match comparison otherwise we should check if starts with. usefull for nested items ("/users/","/users/1", "/users/1/edit")
            if (item?.url) {
                if (item.url === route("admin.dashboard")) {
                    return currentUrl === item.url;
                }

                return currentUrl.startsWith(item.url);
            }

            // the item has children. then we should perform a recursive process with the same method through all children until we find the active one
            if (item?.children) {
                for (const childItem of item.children) {
                    if (this.itemIsActive(childItem)) return true;
                }
            }

            return false;
        },
    },
};
</script>
