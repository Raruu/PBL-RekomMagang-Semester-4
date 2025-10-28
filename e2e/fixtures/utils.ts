export const getTagifyItem = (item: string) => {
    return `.tagify__dropdown__item:has-text("${item}")`;
};
