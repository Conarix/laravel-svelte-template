export function autoFocus(
    node: HTMLElement,
) {
    node.setAttribute('tabindex', node.tabIndex.toString());
    node.focus()
}
