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

  sendRequestBtn: document.getElementById("sendRequestBtn"),
  sendRequestModal: document.getElementById("sendRequestModal"),
  closeSendRequest: document.getElementById("closeSendRequest"),
  cancelSendRequest: document.getElementById("cancelSendRequest"),

  addNewExternalFactorsBtn: document.getElementById("addNewExternalFactorsBtn"),
  addExternalFactorsModal: document.getElementById("addExternalFactorsModal"),
  closeAddExternalFactors: document.getElementById("closeAddExternalFactors"),
  cancelAddExternalFactors: document.getElementById("cancelAddExternalFactors"),

  addGardenEvolutionModal: document.getElementById("addGardenEvolutionModal"),
  closeAddGardenEvolution: document.getElementById("closeAddGardenEvolution"),
  cancelAddGardenEvolution: document.getElementById("cancelAddGardenEvolution"),

}

// ================== INICIO ==================
document.addEventListener("DOMContentLoaded", () => {
  console.log("JS cargado ✅"); 
  initializeEventListeners()
  populateContent()
  generarGraficos()
})

// ================== INICIO ==================
document.addEventListener("DOMContentLoaded", () => {
  console.log("JS cargado ✅");

  initializeEventListeners();
  populateContent();

  generarGraficos();
});

// ================== FUNCIÓN PRINCIPAL ==================
function generarGraficos(){
  if (!window.jardineras || window.jardineras.length === 0) {
    console.log("No hay jardineras ❌");
    return;
  }

  console.log("Jardineras:", window.jardineras);

  window.jardineras.forEach(j => {

    const canvasFactores = document.getElementById("factoresChart" + j.id);
    const canvasTendencia = document.getElementById("tendenciaChart" + j.id);

    // ================== 🌡️ FACTORES ==================
    if (canvasFactores && j.temperatura && j.humedad && j.agua) {

      const ctx = canvasFactores.getContext("2d");

      // destruir gráfico previo si existe
      if (Chart.getChart(canvasFactores)) {
        Chart.getChart(canvasFactores).destroy();
      }

      const length = Math.max(
        j.temperatura.length,
        j.humedad.length,
        j.agua.length
      );

      new Chart(ctx, {
        type: "line",
        data: {
          labels: Array.from({ length }, (_, i) => "R" + (i + 1)),
          datasets: [
            {
              label: "Temperatura",
              data: j.temperatura,
              borderColor: "rgba(255,140,0,1)",
              backgroundColor: "rgba(255,140,0,0.5)",
              tension: 0.3
            },
            {
              label: "Humedad",
              data: j.humedad,
              borderColor: "rgba(0,150,255,1)",
              backgroundColor: "rgba(0,150,255,0.5)",
              tension: 0.3
            },
            {
              label: "Agua",
              data: j.agua,
              borderColor: "rgba(0,200,100,1)",
              backgroundColor: "rgba(0,200,100,0.5)",
              tension: 0.3
            }
          ]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              labels: {
                color: "#2c3e50"
              }
            }
          }
        }
      });
    }

    // ================== 📊 TENDENCIA ==================
    if (canvasTendencia) {

      const datos = j.tendencia && j.tendencia.length > 0 
          ? j.tendencia 
          : [0];

      new Chart(canvasTendencia, {
        type: "bar",
        data: {
          labels: datos.map((_, i) => "Cambio " + (i + 1)),
          datasets: [{
            label: "Tendencia de crecimiento",
            data: datos,
            backgroundColor: "#6C5CE7"
          }]
        }
      });
    }

  });
  
}

// ================== EVENTOS ==================
function initializeEventListeners() {
  // ================== SIDEBAR ==================
  elements.menuToggle?.addEventListener("click", toggleSidebar)
  elements.overlay?.addEventListener("click", closeSidebar)

  const reportButton = elements.generateReportBtn || document.getElementById("generateReportBtn")
  reportButton?.addEventListener("click", generateGardenReportPdf)

  handleReportTypeChange()
  elements.reportType?.addEventListener("change", handleReportTypeChange)
  elements.reportGarden?.addEventListener("change", applyReportFilters)
  elements.reportStartDate?.addEventListener("change", applyReportFilters)
  elements.reportEndDate?.addEventListener("change", applyReportFilters)

  elements.actionCards.forEach(card => {
    card.addEventListener("click", (e) => {

      if (card.dataset.action === "logout") {
        e.preventDefault();
      }

      handleAction(card.dataset.action);
    });
  });
  // ================== LÓGICA DINÁMICA (TIPO SOLICITUD) ==================
  const typeRequest = document.getElementById("typeRequest")
  const newSeedField = document.getElementById("newSeedField")
  const newSeedInput = document.getElementById("newSeed")

  if (typeRequest && newSeedField && newSeedInput) {
    typeRequest.addEventListener("change", () => {
      if (typeRequest.value === "Admisión Nueva Semilla") {
        newSeedField.style.display = "block"
        newSeedInput.required = true
      } else {
        newSeedField.style.display = "none"
        newSeedInput.required = false
      }
    })
  }

  document.addEventListener("click", function (e) {

  // ===== ABRIR =====
  if (e.target.closest("#editProfileBtn")) {
    elements.editProfileModal?.classList.add("active")
  }

  if (e.target.closest("#sendRequestBtn")) {
    elements.sendRequestModal?.classList.add("active")
  }

  // ===== MODAL ACTUALIZAR =====
  const updateBtn = e.target.closest(".updateGardenBtn");
  if (updateBtn) {
    const id = updateBtn.dataset.id;
    document.getElementById("updateGardenId").value = id;
    elements.updateGardenModal?.classList.add("active");
    return;
  }

  // ===== FACTORES EXTERNOS =====
  const externalBtn = e.target.closest(".addNewExternalFactorsBtn");
    if (externalBtn) {
      const id = externalBtn.dataset.id;
      document.getElementById("gardenSelectedId").value = id;
      elements.addExternalFactorsModal?.classList.add("active");
      return;
    }

    // ===== EVOLUCIÓN =====
    const gardenEvolutionBtn = e.target.closest(".addNewGardenEvolutionBtn");
    if (gardenEvolutionBtn) {
      const id = gardenEvolutionBtn.dataset.id;
      const faseId=gardenEvolutionBtn.dataset.fase;
      console.log(id, faseId);

      document.getElementById("gardenEvolutionId").value = id;
      document.getElementById("faseEvolutionId").value = faseId;

      cargarPreguntas(faseId);

      elements.addGardenEvolutionModal?.classList.add("active");
      return;
    }

    // ===== CERRAR =====
    if (e.target.closest("#closeEditProfile") || e.target.closest("#cancelEditProfile")) {
      elements.editProfileModal?.classList.remove("active")
    }

    if (e.target.closest("#closeSendRequest") || e.target.closest("#cancelSendRequest")) {
      elements.sendRequestModal?.classList.remove("active")
    }

    if (e.target.closest("#closeUpdateGarden") || e.target.closest("#cancelUpdateGarden")) {
      elements.updateGardenModal?.classList.remove("active")
    }

    if (e.target.closest("#closeAddExternalFactors") || e.target.closest("#cancelAddExternalFactors")) {
      elements.addExternalFactorsModal?.classList.remove("active")
    }

    if (e.target.closest("#closeGardenEvolution") || e.target.closest("#cancelAddGardenEvolution")) {
      elements.addGardenEvolutionModal?.classList.remove("active")
    }
  });
  
  // Cerrar al hacer click fuera del modal
  document.querySelectorAll(".modal").forEach(modal => {
    modal.addEventListener("click", function (e) {
      if (e.target === modal) {
        modal.classList.remove("active")
      }
    })
  })
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
  if (action === "add-view--external-factors") navigateToPage("externalFactors")
  if (action === "add-view-garden-evolution") navigateToPage("gardenEvolution")
  if (action === "view-gardens") navigateToPage("gardens")
  if (action === "view-monitoring") navigateToPage("monitoring")
  if (action === "view-report") navigateToPage("reports")
  if (action === "view-request") navigateToPage("request")
  if (action === "generate-report") showNotification("Generando reporte...")

  if(action === "view-users") navigateToPage("users")
  if(action === "view-admin-gardens") navigateToPage("admin-gardens")
  if(action === "view-seeds") navigateToPage("seeds")
  if(action === "add-seed") navigateToPage("add-seed")
  if (action === "logout") cerrarSesion()
}

// ================== PERFIL ==================
function closeSendRequestModal() {
  elements.sendRequestModal.classList.remove("active")
}

function closeUpdateGardenModal() {
  elements.updateGardenModal.classList.remove("active")
}

function closeAddExternalFactorsModal() {
  elements.addExternalFactorsModal.classList.remove("active")
}

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

  const filteredGardens = filterGardenReportData()

  if (!filteredGardens || filteredGardens.length === 0) {
    elements.reportsList.innerHTML = '<p class="empty-report">No hay jardineras registradas para mostrar en el reporte.</p>'
    return
  }

  const fragment = document.createDocumentFragment()

  filteredGardens.forEach((garden, index) => {
    const item = document.createElement('div')
    item.className = 'report-item'
    item.innerHTML = `
      <div class="report-header">
        <h3>Jardinera ${index + 1}: ${garden.jarNombre || 'Sin nombre'}</h3>
        <span class="report-status completed">${garden.faseNombre || 'Fase desconocida'}</span>
      </div>
      <div class="report-description">
        <p>${garden.jarDescripcion || 'No hay descripción disponible.'}</p>
      </div>
      <div class="report-meta">
        <span>Semilla: ${garden.semNombre || 'N/A'}</span>
        <span>Progreso: ${garden.jarPorcentajeEvolucion || '0'}%</span>
        <span>Creada: ${garden.jarFechaCreacion || 'N/A'}</span>
      </div>
    `
    fragment.appendChild(item)
  })

  elements.reportsList.appendChild(fragment)
}

function handleReportTypeChange() {
  const isCustom = elements.reportType?.value === 'custom'
  if (elements.reportCustomRange) {
    elements.reportCustomRange.style.display = isCustom ? 'grid' : 'none'
  }
  applyReportFilters()
}

function filterGardenReportData() {
  const reportType = elements.reportType?.value || 'monthly'
  const reportGarden = elements.reportGarden?.value || 'all'
  const startDateValue = elements.reportStartDate?.value || ''
  const endDateValue = elements.reportEndDate?.value || ''
  const source = Array.isArray(window.gardenReportData) ? window.gardenReportData : []

  const now = new Date()
  const weekAgo = new Date(now)
  weekAgo.setDate(weekAgo.getDate() - 7)
  const monthAgo = new Date(now)
  monthAgo.setDate(monthAgo.getDate() - 30)

  return source.filter((garden) => {
    const createdDate = garden.jarFechaCreacion ? new Date(garden.jarFechaCreacion) : null
    let dateMatches = true

    if (reportType === 'weekly') {
      dateMatches = createdDate && createdDate >= weekAgo && createdDate <= now
    } else if (reportType === 'monthly') {
      dateMatches = createdDate && createdDate >= monthAgo && createdDate <= now
    } else if (reportType === 'custom' && startDateValue && endDateValue) {
      const startDate = new Date(startDateValue)
      const endDate = new Date(endDateValue)
      endDate.setHours(23, 59, 59, 999)
      if (!isNaN(startDate) && !isNaN(endDate)) {
        dateMatches = createdDate && createdDate >= startDate && createdDate <= endDate
      }
    }

    let gardenMatches = true
    if (reportGarden !== 'all') {
      gardenMatches = garden.idSemilla && garden.idSemilla.toString() === reportGarden
    }

    return dateMatches && gardenMatches
  })
}

function applyReportFilters() {
  populateReports()
}

function generateGardenReportPdf() {
  console.log('Generar reporte PDF', window.gardenReportData, window.userReportData)

  const selectedGardens = filterGardenReportData()
  if (!selectedGardens || selectedGardens.length === 0) {
    showNotification('No hay jardineras que cumplan con el filtro seleccionado.')
    return
  }

  const jsPDFConstructor = window.jspdf?.jsPDF || window.jsPDF
  if (!jsPDFConstructor) {
    showNotification('La librería jsPDF no está cargada. Verifica la conexión o el archivo local.')
    return
  }

  const doc = new jsPDFConstructor({ unit: 'pt', format: 'a4' })
  const pageWidth = doc.internal.pageSize.width
  const pageHeight = doc.internal.pageSize.height
  const margin = 40
  const contentWidth = pageWidth - margin * 2
  let cursorY = margin

  const addPageIfNeeded = (height) => {
    if (cursorY + height > pageHeight - margin) {
      doc.addPage()
      cursorY = margin
    }
  }

  // Header
  doc.setFillColor(39, 105, 217)
  doc.rect(0, 0, pageWidth, 72, 'F')
  doc.setTextColor(255, 255, 255)
  doc.setFontSize(22)
  doc.text('BioUrbis - Reporte de Jardineras', margin, 44)
  doc.setFontSize(10)
  doc.text(`Fecha: ${new Date().toLocaleDateString('es-CO')}`, pageWidth - margin, 44, { align: 'right' })

  cursorY = 100

  // Report details
  const reportType = elements.reportType?.value || 'monthly'
  const reportTypeLabel = reportType === 'weekly' ? 'Semanal' : reportType === 'custom' ? 'Personalizado' : 'Mensual'
  const selectedGardenLabel = elements.reportGarden?.selectedOptions?.[0]?.text || 'Todas las semillas'
  addPageIfNeeded(28)
  doc.setFontSize(10)
  doc.setTextColor(255, 255, 255)
  doc.text(`Tipo de reporte: ${reportTypeLabel}`, margin + 12, cursorY)
  doc.text(`Semilla: ${selectedGardenLabel}`, pageWidth - margin, cursorY, { align: 'right' })
  cursorY += 24

  // User data section
  const userInfo = window.userReportData || {}
  addPageIfNeeded(84)
  doc.setFillColor(245, 248, 255)
  doc.setDrawColor(206, 216, 247)
  doc.rect(margin, cursorY, contentWidth, 76, 'FD')
  doc.setTextColor(30, 30, 30)
  doc.setFontSize(12)
  doc.text('Usuario', margin + 12, cursorY + 22)
  doc.setFontSize(10)
  doc.text(`Nombre: ${userInfo.nombre || 'No disponible'}`, margin + 12, cursorY + 38)
  doc.text(`Documento: ${userInfo.documento || 'No disponible'}`, margin + 12, cursorY + 54)
  doc.text(`Correo: ${userInfo.correo || 'No disponible'}`, margin + contentWidth / 2, cursorY + 38)
  doc.text(`Barrio: ${userInfo.barrio || 'No disponible'}`, margin + contentWidth / 2, cursorY + 54)

  cursorY += 100

  // Gardens sections
  selectedGardens.forEach((garden, index) => {
    addPageIfNeeded(22)
    doc.setFontSize(14)
    doc.setTextColor(21, 40, 85)
    doc.text(`Jardinera ${index + 1}: ${garden.jarNombre || 'Sin nombre'}`, margin, cursorY)
    cursorY += 20

    addPageIfNeeded(110)
    doc.setFillColor(250, 250, 252)
    doc.setDrawColor(220, 224, 236)
    doc.roundedRect(margin, cursorY, contentWidth, 110, 6, 6, 'FD')
    doc.setFontSize(10)
    doc.setTextColor(51, 51, 51)

    const gardenDetails = [
      `Descripción: ${garden.jarDescripcion || 'No disponible'}`,
      `Semilla: ${garden.semNombre || 'N/A'}`,
      `Fase actual: ${garden.faseNombre || 'N/A'}`,
      `Progreso: ${garden.jarPorcentajeEvolucion || '0'}%`,
      `Creación: ${garden.jarFechaCreacion || 'N/A'}`
    ]
    doc.text(gardenDetails, margin + 12, cursorY + 18, { maxWidth: contentWidth - 24 })
    cursorY += 82

    if (garden.factoresExternos && garden.factoresExternos.length > 0) {
      addPageIfNeeded(18)
      doc.setFontSize(11)
      doc.setTextColor(39, 79, 142)
      doc.text('Factores externos', margin + 12, cursorY + 8)
      cursorY += 18

      garden.factoresExternos.forEach((factor) => {
        addPageIfNeeded(16)
        doc.setFontSize(10)
        doc.setTextColor(40, 40, 40)
        const factorText = `• Humedad: ${factor.humedad || 'N/A'} | Agua: ${factor.cantidadAgua || 'N/A'} | Temperatura: ${factor.temperatura || 'N/A'} | Clima: ${factor.clima || 'N/A'}`
        const lines = doc.splitTextToSize(factorText, contentWidth - 32)
        doc.text(lines, margin + 18, cursorY)
        cursorY += lines.length * 12 + 4
      })
      cursorY += 4
    }

    if (garden.evoluciones && garden.evoluciones.length > 0) {
      addPageIfNeeded(18)
      doc.setFontSize(11)
      doc.setTextColor(39, 79, 142)
      doc.text('Evolución', margin + 12, cursorY + 8)
      cursorY += 18

      garden.evoluciones.forEach((evolucion, evoIndex) => {
        addPageIfNeeded(16)
        doc.setFontSize(10)
        doc.setTextColor(40, 40, 40)
        const evolutionText = `${evoIndex + 1}. ${evolucion.fecha || 'Fecha no registrada'} — ${evolucion.nota || 'Sin nota'} (${evolucion.porcentaje || '0'}%)`
        const lines = doc.splitTextToSize(evolutionText, contentWidth - 32)
        doc.text(lines, margin + 18, cursorY)
        cursorY += lines.length * 12 + 4
      })
      cursorY += 10
    }
  })

  doc.setTextColor(120, 120, 120)
  doc.setFontSize(9)
  doc.text('Generado por BioUrbis', margin, pageHeight - 28)

  doc.save('reporte_jardineras.pdf')
  showNotification('Reporte descargado correctamente.')
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
function subirImagen() {
  document.getElementById("imgAvatar").click();
}

function enviarFormulario() {
  document.getElementById("imgAvatar").form.submit();
}

function cargarPreguntas(idFase) {
  fetch("../php/obtenerPreguntas.php?idFase=" + idFase)
    .then(res => res.text())
    .then(html => {
      document.getElementById("contenedorPreguntas").innerHTML = html;
    });
}



