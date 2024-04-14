async function doHypermediaCall(elem, url) {
  const response = await fetch(url);
  if (response.ok) {
    elem.innerHTML = await response.text();

    for (const anchor of elem.querySelectorAll("a")) {
      anchor.addEventListener("click", async (e) => {
        e.preventDefault();
        await doHypermediaCall(elem, anchor.getAttribute("href"));
      });
    }

    for (const inputElem of elem.querySelectorAll("input[data-change-param]")) {
      inputElem.addEventListener("change", async () => {
        const newUrl = new URL(url);
        newUrl.searchParams.set(
          inputElem.getAttribute("data-change-param"),
          inputElem.value
        );
        await doHypermediaCall(elem, newUrl.href);
      });
    }
  }
}

window.addEventListener("load", async () => {
  const elems = document.body.querySelectorAll("[data-load-href]");
  for (const elem of elems) {
    const url = window.location.origin + elem.getAttribute("data-load-href");
    await doHypermediaCall(elem, url);
  }
});
