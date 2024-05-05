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
  } else {
    console.log("API error", response);
    elem.dispatchEvent(new Event("apiError", { bubbles: true }));
  }
}

function handleGlobalApiError() {
  const msgElem = document.getElementById("error-message");
  msgElem.textContent = "An API error occurred";
  msgElem.style.display = "block";
  setTimeout(() => {
    msgElem.style.display = "none";
  }, 2000);
}

window.addEventListener("load", async () => {
  document.body.addEventListener("apiError", handleGlobalApiError);
  const elems = document.body.querySelectorAll("[data-load-href]");
  for (const elem of elems) {
    const url = window.location.origin + elem.getAttribute("data-load-href");
    await doHypermediaCall(elem, url);
  }
});
