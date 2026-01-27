function announcementRemove(e) {
   let t = document.querySelector("[data-announcement]");
   t && (t.classList.add("opacity-0"), t.classList.add("grid-rows-[0fr]"), t.classList.remove("grid-rows-[1fr]"), t.classList.remove("opacity-100"), localStorage.setItem("announcements", new Date().getTime()));
}
function announcementShow(e) {
   let t = document.querySelector("[data-announcement]");
   t && (t.classList.remove("opacity-0"), t.classList.remove("grid-rows-[0fr]"), t.classList.add("grid-rows-[1fr]"), t.classList.add("opacity-100"));
}
function toggleBookmark(e) {
   if (!window.user) return (window.location.href = "/login");
   e.target.classList.contains("text-primary") ? removeBookmark(e) : addBookmark(e);
}
function addBookmark(e) {
   let { id: t, bookmarkType: r, bookmarkAdd: a } = e.target.dataset;
   fetch("/api/bookmark", { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ bookmarkType: r, bookmarkAdd: a, id: t }) })
      .then((e) => e.json())
      .then((t) => {
         200 === t.status ? e.target.classList.add("text-primary") : console.log("error");
      })
      .catch((e) => console.log(e));
}
function removeBookmark(e) {
   let { id: t, bookmarkType: r, bookmarkAdd: a } = e.target.dataset;
   fetch("/api/bookmark", { method: "DELETE", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ bookmarkType: r, bookmarkAdd: a, id: t }) })
      .then((e) => e.json())
      .then((t) => {
         200 === t.status ? e.target.classList.remove("text-primary") : console.log("error");
      })
      .catch((e) => console.log(e));
}
function navigateTo(e) {
   if (!e) e = window.location.pathname;
   document.cookie = `lastPage=${window.location.pathname}; path=/; max-age=60`;
   window.location.href = e;
}
window.addEventListener("load", () => {
   let e = document.querySelectorAll("[data-select]");
   e.forEach((e) => {
      let t = e.querySelector("[data-select-content]");
      e.addEventListener("click", (r) => {
         t.classList.toggle("active"), e.classList.toggle("active");
      });
   });
   let t = document.querySelectorAll("[data-slideable-section]");
   t.forEach((e) => {
      e.addEventListener("click", (t) => {
         let r = document.querySelector(`[data-slideable-section="${e.dataset.slideableSection}"].text-white`);
         r.classList.remove("border-white/75"), r.classList.remove("bg-active-background"), r.classList.remove("text-white"), e.classList.add("border-white/75"), e.classList.add("bg-active-background"), e.classList.add("text-white");
         let a = document.querySelector(`[data-slideable-section-content="${e.dataset.slideableSection}_${t.target.getAttribute("for")}"]`),
            s = document.querySelector(`[data-slideable-section-content-wrapper="${e.dataset.slideableSection}"]`),
            o = a.getBoundingClientRect().left,
            l = s.getBoundingClientRect().left;
         s.style.transform = `translateX(-${o - l}px)`;
      });
   });
   let r = document.querySelectorAll("[data-stars]");
   r.forEach((e) => {
      e.querySelectorAll("img").forEach((t) => {
         t.addEventListener("click", (t) => {
            let r = Array.from(e.children).indexOf(t.target) + 1,
               a = Array.from(e.querySelectorAll("img"));
            a.forEach((e) => {
               e.src = "/images/star_gray.svg";
            });
            for (let s = 0; s < r; s++) a[s].src = "/images/star.svg";
            e.dataset.stars = r;
         });
      }),
         e.addEventListener("mousemove", (t) => {
            if ("IMG" !== t.target.tagName) return;
            let r = Array.from(e.children).indexOf(t.target) + 1,
               a = Array.from(e.querySelectorAll("img"));
            a.forEach((e) => {
               e.src = "/images/star_gray.svg";
            });
            for (let s = 0; s < r; s++) a[s].src = "/images/star.svg";
         }),
         e.addEventListener("mouseleave", (t) => {
            let r = e.dataset.stars,
               a = Array.from(e.querySelectorAll("img"));
            a.forEach((e) => {
               e.src = "/images/star_gray.svg";
            });
            for (let s = 0; s < r; s++) a[s].src = "/images/star.svg";
         });
   });
   let a = document.querySelector("[data-slider]"),
      s = new IntersectionObserver(
         (e) => {
            e.forEach((e) => {
               if (e.isIntersecting) {
                  e.target.classList.add("active");
                  let t = Array.from(a.children).indexOf(e.target),
                     r = document.querySelector("[data-indicators] > *:nth-child(" + (t + 1) + ")");
                  r.classList.add("bg-white"), r.classList.remove("gradient-background-gray");
               } else {
                  let s = Array.from(a.children).indexOf(e.target);
                  e.target.classList.remove("active");
                  let o = document.querySelector("[data-indicators] > *:nth-child(" + (s + 1) + ")");
                  o.classList.remove("bg-white"), o.classList.add("gradient-background-gray");
               }
            });
         },
         { threshold: 0.51 }
      ),
      o = document.querySelectorAll("[data-slide]");
   o.forEach((e) => {
      s.observe(e);
   });
   let l = document.querySelectorAll("[data-indicators] > *");
   l.forEach((e) => {
      e.addEventListener("click", (e) => {
         let t = o[Array.from(l).indexOf(e.target)];
         a.scrollLeft = t.offsetLeft;
      });
   });
   let i = 0,
      n = 0,
      c = new IntersectionObserver(
         (e) => {
            e.forEach((e) => {
               e.isIntersecting
                  ? (e.target.classList.remove("active"),
                    (n = setInterval(() => {
                       if (++i > 10) {
                          let e = document.querySelector("[data-slider] > .active");
                          if (!e) return;
                          let t = e.nextElementSibling || o[0];
                          (a.scrollLeft = t.offsetLeft), (i = 0);
                       }
                    }, 1e3)))
                  : clearInterval(n);
            });
         },
         { threshold: 0.51 }
      );
   a && c.observe(a);
   let d = document.querySelector("[data-navbar-toggle]"),
      g = document.querySelector("[data-navbar]");
   d?.addEventListener("click", () => {
      g.classList.toggle("grid-rows-[1fr]"), g.classList.toggle("grid-rows-[0fr]");
   });
   let m = window.localStorage.getItem("announcements");
   (!m || new Date().getTime() - parseInt(m) > 864e5) && announcementShow();
   let u = document.querySelectorAll("[data-image-input]");
   u.forEach((e) => {
      e.addEventListener("change", (t) => {
         let r = t.target.files[0],
            a = new FileReader();
         (a.onloadend = () => {
            e.parentElement.querySelector("[data-image-preview]").src = a.result;
         }),
            a.readAsDataURL(r);
      });
   });
   let h = document.cookie
         .split(";")
         ?.find((e) => e.includes("token"))
         ?.split("=")[1],
      y = document.querySelector("[data-login]"),
      f = document.querySelector("[data-login-svg]"),
      qq = document.querySelectorAll("[data-login-href]");
   h
      ? fetch("/api/me")
           .then((e) => e.json())
           .then((e) => {
              let t = document.querySelector("[data-history]"),
                 r = document.querySelectorAll("[data-bookmark]"),
                 a = document.querySelector("[data-comment-input]"),
                 s = document.querySelector("[data-comment-login]");
              console.log(qq);
              if (200 === e.status) {
                 e.user.adsw || document.querySelectorAll(".adsw").forEach((e) => e.remove());
                 let o = document.createElement("img");
                 if (
                    ((o.src = e.user.avatar),
                    o.classList.add("w-4", "h-4", "rounded-full"),
                    f.parentElement.insertBefore(o, f),
                    f.remove(),
                    (window.user = e.user),
                    (y.innerText = e.user.username),
                    (y.parentElement.href = "/user/" + e.user.id),
                    a && a.classList.remove("hidden"),
                    s && s.classList.add("hidden"),
                    t)
                 ) {
                    let l = document.querySelector("[data-history-login]");
                    l.remove(),
                       fetch("/api/history/" + t.dataset.history)
                          .then((e) => e.json())
                          .then((e) => {
                             if (200 == e.status) {
                                let r = t.querySelector("[data-history-text] a"),
                                   a = t.querySelector("[data-history-text] span"),
                                   s = t.querySelector("[data-history-progress]"),
                                   o = t.querySelector("[data-history-ago]");
                                (r.innerText = e.history.episode.name), (s.style.width = e.history.percent + "%"), (o.innerText = "Last read " + e.history.ago), o.classList.remove("hidden"), (a.textContent = "Continue to ");
                             }
                          });
                 }
                 if (r.length) {
                    let i = Array.from(r).map((e) => ({ typeId: e.dataset.id, type: e.dataset.bookmarkType }));
                    fetch("/api/bookmarks", { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ ids: i }) })
                       .then((e) => e.json())
                       .then((e) => {
                          if (200 == e.status)
                             for (let t = 0; t < e.data.length; t++) {
                                let r = e.data[t];
                                document.querySelectorAll(`[data-bookmark="${r.typeId}"]`).forEach((e) => {
                                   e.querySelectorAll("[data-bookmark-add]").forEach((e) => {
                                      r.bookmarks.find((t) => t.bookmarkType.toLowerCase() == e.dataset.bookmarkAdd.toLowerCase()) ? e.classList.add("text-primary") : e.classList.remove("text-primary");
                                   });
                                });
                             }
                       });
                 }
              } else
                 (y && (y.innerText = "Đăng Nhập")),
                    (y && (y.parentElement.href = "/login")),
                    Array.from(qq).map((e) => (e.href = "/login")),
                    document.querySelectorAll("a[href='/login']").forEach((link) => {
                       link.addEventListener("click", (e) => {
                          e.preventDefault();
                          navigateTo("/login");
                       });
                    });
           })
      : y && (y.innerText = "Đăng Nhập"),
      y && (y.parentElement.href = "/login");
   if (!h) {
      document.querySelectorAll("a[href='/login']").forEach((link) => {
         link.addEventListener("click", (e) => {
            e.preventDefault();
            navigateTo("/login");
         });
      });
   }
   let L = document.querySelectorAll(".hottestButton");
   L.forEach((e) => {
      e.addEventListener("click", (t) => {
         fetch("/api/hottest-categories?category=" + e.dataset.id)
            .then((e) => e.text())
            .then((e) => {
               document.querySelector("[data-hottest-result]").innerHTML = e;
               let r = document.querySelector(".hottestButton.bg-active-background");
               r.classList.remove("bg-active-background", "text-white", "border-white/75"), r.classList.add("text-lightgray", "border-border-gray"), t.target.classList.add("bg-active-background", "text-white", "border-white/75"), t.target.classList.remove("text-lightgray", "border-border-gray");
            });
      });
   });

   let allDropdownInputs = document.querySelectorAll("[name='dropdown']");
   let dropDownOpen = false;
   allDropdownInputs.forEach((input) => {
      input.addEventListener("change", (e) => {
         dropDownOpen = e.target.checked;
      });
   });

   document.addEventListener("click", (e) => {
      if (dropDownOpen) {
         allDropdownInputs.forEach((input) => {
            input.checked = false;
         });
         dropDownOpen = false;
      }
   });

   let dataSearch = document.querySelectorAll("[data-search]");

   dataSearch.forEach((b) => {
      b.addEventListener(
         "click",
         (e) => {
            e.preventDefault();
            let searchPath = e.target.getAttribute("data-search");
            document.cookie = `search=${searchPath}; path=/; max-age=1`;
            window.location.href = "/search";
         },
         { once: true }
      );
   });

   const everyATag = Array.from(document.querySelectorAll("a")).filter((a) => a.href.includes("/series/") || a.href.includes("/read/"));
   everyATag.forEach((a) => {
      if(JSON.parse(localStorage.getItem("clickedLinks") || "[]").includes(a.href)) {
         a.classList.add("text-primary");
         a.querySelector(".gradient-text-gray")?.classList.remove("gradient-text-gray");
      }
      a.addEventListener("click", (e) => {
         const alreadyClickedLinks = JSON.parse(localStorage.getItem("clickedLinks"));
         if (alreadyClickedLinks) {
            alreadyClickedLinks.push(a.href);
            localStorage.setItem("clickedLinks", JSON.stringify(alreadyClickedLinks));
         } else {
            localStorage.setItem("clickedLinks", JSON.stringify([a.href]));
         }
      });
   });

   const login_error_cookie = getCookie("login_error")
   const login_success_cookie = getCookie("login_success")

   if (login_error_cookie) {
      document.querySelector("#login_error").innerHTML = decodeURIComponent(login_error_cookie);
      document.querySelector("#login_error").classList.remove("hidden");
      deleteCookie("login_error")
   }
   if (login_success_cookie) {
      document.querySelector("#login_success").innerHTML = decodeURIComponent(login_success_cookie);
      document.querySelector("#login_success").classList.remove("hidden");
      deleteCookie("login_success")
   }

   function getCookie(name) {
      var nameEQ = name + "=";
      var ca = document.cookie.split(';');
      for (var i = 0; i < ca.length; i++) {
         var c = ca[i];
         while (c.charAt(0) == ' ') c = c.substring(1, c.length);
         if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
      }
      return null;
   }
   function deleteCookie(name) {
      document.cookie = name + '=; Max-Age=-99999999;';
   }

   const bookmarks = document.querySelectorAll("[data-bookmark]");
   bookmarks.forEach((bookmark) => {
      bookmark.addEventListener("click", (e) => {
         if(dropDownOpen) {
            const bookmarkId = e.target.dataset.bookmark;
            dropDownOpen = false;
            document.getElementById("manga_" + bookmarkId).checked = false;
            return;
         }
      })
   });
});
