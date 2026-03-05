// ================== ESTADO GLOBAL ==================
const appState = {
  selectedSeeds: [],
  activities: [],
  gardens: [],
  reports: [],
  posts: [],
  members: [],
  events: [],
  alerts: [],
  seeds: [],
  monitoring: {
    parameters: []
  }
}

// ================== ELEMENTOS ==================
const elements = {
  menuToggle: document.getElementById("menuToggle"),
  sidebar: document.getElementById("sidebar"),
  overlay: document.getElementById("overlay"),
  backBtn: document.getElementById("backBtn"),
  searchInput: document.getElementById("searchInput"),
  searchBtn: document.getElementById("searchBtn"),
  navItems: document.querySelectorAll(".nav-item"),
  pages: document.querySelectorAll(".page"),
  actionCards: document.querySelectorAll(".action-card"),
  editProfileBtn: document.getElementById("editProfileBtn"),
  editAvatarBtn: document.getElementById("editAvatarBtn"),
  editProfileModal: document.getElementById("editProfileModal"),
  closeEditProfile: document.getElementById("closeEditProfile"),
  cancelEditProfile: document.getElementById("cancelEditProfile"),
  editProfileForm: document.getElementById("editProfileForm"),
  addGardenForm: document.getElementById("addGardenForm"),
  cancelAddGarden: document.getElementById("cancelAddGarden"),
  addNewGardenBtn: document.getElementById("addNewGardenBtn"),
  generateReportBtn: document.getElementById("generateReportBtn"),
  updateGardenModal: document.getElementById("updateGardenModal"),
  closeUpdateGarden: document.getElementById("closeUpdateGarden"),
  cancelUpdateGarden: document.getElementById("cancelUpdateGarden"),
  updateGardenForm: document.getElementById("updateGardenForm"),
  reportsList: document.getElementById("reportsList"),
}

// ================== NAVEGACIÓN ==================
function navigateToPage(page) {
  document.querySelectorAll(".page").forEach(p => p.classList.remove("active"))
  document.getElementById(`${page}-page`)?.classList.add("active")

  document.querySelectorAll(".nav-item").forEach(item => {
    item.classList.toggle("active", item.dataset.page === page)
  })
}

// Alias para llamadas antiguas
function showPage(page) {
  navigateToPage(page)
}

// ================== INICIO ==================
document.addEventListener("DOMContentLoaded", () => {
  initializeEventListeners()
  populateContent()
  navigateToPage("profile")
})

// ================== EVENTOS ==================
function initializeEventListeners() {

  elements.menuToggle?.addEventListener("click", toggleSidebar)
  elements.overlay?.addEventListener("click", closeSidebar)

  elements.navItems.forEach(item => {
    item.addEventListener("click", e => {
      e.preventDefault()
      navigateToPage(item.dataset.page)
    })
  })

  elements.actionCards.forEach(card => {
    card.addEventListener("click", () =>
      handleAction(card.dataset.action)
    )
  })

  elements.editProfileBtn?.addEventListener("click", () =>
    elements.editProfileModal.classList.add("active")
  )

  elements.closeEditProfile?.addEventListener("click", closeEditProfileModal)
  elements.cancelEditProfile?.addEventListener("click", closeEditProfileModal)
}

// ================== SIDEBAR ==================
function toggleSidebar() {
  elements.sidebar.classList.toggle("active")
  elements.overlay.classList.toggle("active")
}

function closeSidebar() {
  elements.sidebar.classList.remove("active")
  elements.overlay.classList.remove("active")
}

// ================== ACCIONES ==================
function handleAction(action) {
  if (action === "view-profile") navigateToPage("profile")
  if (action === "add-garden") navigateToPage("add-garden")
  if (action === "view-gardens") navigateToPage("gardens")
  if (action === "view-monitoring") navigateToPage("monitoring")
  if (action === "view-report") navigateToPage("reports")
  if (action === "generate-report") showNotification("Generando reporte...")
  if (action === "Logout") cerrarSesion()
}

// ================== PERFIL ==================
function closeEditProfileModal() {
  elements.editProfileModal.classList.remove("active")
}

// ================== CONTENIDO ==================
function populateContent() {
  populateActivities()
  populateGardens()
  populateReports()
  populateCommunity()
  populateAlerts()
  populateSeeds()
  populateMonitoring()
}

// ================== ACTIVIDAD ==================
function populateActivities() {
  const container = document.getElementById("activityList")
  if (!container) return
  container.innerHTML = ""
}

// ================== JARDINES ==================
function populateGardens() {
  const container = document.getElementById("gardensGrid")
  if (!container) return
  container.innerHTML = ""
}

// ================== REPORTES ==================
function populateReports() {
  if (!elements.reportsList) return
  elements.reportsList.innerHTML = ""
}

function downloadReport(id) {
  showNotification("Descargando reporte " + id)
}

function deleteReport(id) {
  showNotification("Reporte eliminado")
}

// ================== SEMILLAS ==================
function populateSeeds() {
  const grid = document.getElementById("seedsGrid")
  if (!grid) return
  grid.innerHTML = ""
}

function toggleSeed(seedId) {
  const idx = appState.selectedSeeds.indexOf(seedId)
  if (idx === -1) appState.selectedSeeds.push(seedId)
  else appState.selectedSeeds.splice(idx, 1)

  populateSeeds()
}

// ================== UTILIDADES JARDINES ==================
function groupSeedsByCategory(seeds) {
  const map = {}
  seeds.forEach(s => {
    if (!map[s.category]) map[s.category] = []
    map[s.category].push(s)
  })
  return Object.entries(map)
}

function getCategoryColor(category) {
  return {
    frutas: "#f59e0b",
    vegetales: "#059669",
    hierbas: "#8b5cf6"
  }[category] || "#999"
}

function getCategoryName(category) {
  return {
    frutas: "Frutas",
    vegetales: "Vegetales",
    hierbas: "Hierbas Medicinales"
  }[category] || category
}

// ================== VER / EDITAR JARDÍN ==================
function viewGarden(id) {
  console.log("Ver jardín", id)
}

function editGarden(id) {
  console.log("Editar jardín", id)
}

// ================== COMUNIDAD ==================
function populateCommunity() {}

function likePost(id) {
  console.log("Like post", id)
}

function contactMember() {}
function joinEvent() {}

// ================== ALERTAS ==================
function populateAlerts() {
  const alertsList = document.getElementById("alertsList")
  if (!alertsList) return
  alertsList.innerHTML = ""
}

// ================== MONITOREO ==================
function populateMonitoring() {}

// ================== NOTIFICACIONES ==================
function showNotification(msg) {
  if (window.Swal) {
    Swal.fire("Info", msg, "info")
  } else {
    alert(msg)
  }
}

// ================== EXPOSICIÓN GLOBAL ==================
Object.assign(window, {
  viewGarden,
  editGarden,
  deleteReport,
  downloadReport,
  toggleSeed,
  likePost,
  contactMember,
  joinEvent
})

function cerrarSesion() {
  console.log("🔥 cerrarSesion ejecutada");
  mostrarMensaje({
    title:"¿Desea cerrar sesión?",
    text:"Si cierra sesión, deberá volver a ingresar sus credenciales para acceder a su cuenta",
    icon:"error",
                                                            
    //Si el usuario acepta cerrar sesión, redirigir a la página de inicio de sesión
    rutaTrue:"../forms/formAcceso.php",

    //Si el usuario no acepta cerrar sesión, redirigir a la página de inicio del usuario
    rutaFalse:"../php/homeUsuario.php"
  })
}
