// @ts-nocheck
import Tagify from "@yaireo/tagify";
window.Tagify = Tagify;

import DragSort from "@yaireo/dragsort";
window.DragSort = DragSort;

const onDragEnd = (tagify) => {
    tagify.updateValueByDOMTags();
};
window.tagifyUtils = {
    onDragEnd: onDragEnd,
};
