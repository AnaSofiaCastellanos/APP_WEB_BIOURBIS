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
  reportType: document.getElementById("reportType"),
  reportGarden: document.getElementById("reportGarden"),
  reportCustomRange: document.getElementById("reportCustomRange"),
  reportStartDate: document.getElementById("reportStartDate"),
  reportEndDate: document.getElementById("reportEndDate"),

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

  generateNewExternalFactorsBtn:document.getElementById("generateNewExternalFactorsBtn"),
  generateExternalFactorsModal:document.getElementById("generateExternalFactorsModal"),
  closeGenerateExternalFactors:document.getElementById("closeGenerateExternalFactors"),
  generateExternalFactorsForm:document.getElementById("generateExternalFactorsForm"),
  cancelGenerateExternalFactors:document.getElementById("cancelGenerateExternalFactor")
}

// ================== INICIO ==================
document.addEventListener("DOMContentLoaded", () => {
  console.log("JS cargado ✅"); 
  initializeEventListeners();
  generarGraficos();
})

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
              borderColor: "rgba(255,80,80,1)",
              backgroundColor: "rgba(255,99,71,0.2)",
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
              borderColor: "rgba(0,180,255,1)",
              backgroundColor: "rgba(0,180,255,0.5)",
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
            backgroundColor: "rgba(255,140,0,1)"
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

      // ===== GENERAR FACTORES EXTERNOS =====
      const generateExternalBtn = e.target.closest(".generateNewExternalFactorsBtn");
      if (generateExternalBtn) {
        const id = generateExternalBtn.dataset.id;
        document.getElementById("gardenIdGenerateExternalFactor").value = id;
        elements.generateExternalFactorsModal?.classList.add("active");
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

      if (e.target.closest("#closeGenerateExternalFactors") || e.target.closest("#cancelGenerateExternalFactors")) {
        elements.generateExternalFactorsModal?.classList.remove("active")
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
        <h3>Jardinera: ${garden.jarNombre || 'Sin nombre'}</h3>
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
      gardenMatches = garden.idJardinera && garden.idJardinera.toString() === reportGarden
    }

    return dateMatches && gardenMatches
  })
}

function applyReportFilters() {
  populateReports()
}

async function generateGardenReportPdf() {
  console.log('Generar reporte PDF', window.gardenReportData, window.userReportData, window.alertas)

  const selectedGardens = filterGardenReportData()
  if (!selectedGardens || selectedGardens.length === 0) {
    mostrarMensaje({
      title:"¡Error a la hora de generar el reporte!",
      text:"No hay jardineras que cumplan con el filtro seleccionado para generar el reporte. Por favor, ajusta los filtros o agrega jardineras para poder generar el reporte correctamente",
      icon:"error",
                                                            
      //Si el usuario acepta ajustar los filtros o agregar jardineras
      rutaTrue:"homeUsuario.php?page=reports",

      //Si el usuario no acepta ajustar los filtros o agregar jardineras
      rutaFalse:"homeUsuario.php?page=reports"
    })
    return
  }

  const jsPDFConstructor = window.jspdf?.jsPDF || window.jsPDF
  if (!jsPDFConstructor) {
    mostrarMensaje({
      title:"¡Error a la hora de generar el reporte!",
      text:"La librería jsPDF no está cargada, le sugerimos recargar la página para intentar nuevamente",
      icon:"error",
                                                            
      //Si el usuario acepta recargar la pagina 
      rutaTrue:"homeUsuario.php?page=reports",

      //Si el usuario no acepta recargar la pagina
      rutaFalse:"homeUsuario.php?page=reports"
    })
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

  const loadImageAsDataUrl = (src) => new Promise((resolve, reject) => {
    const image = new Image()
    image.crossOrigin = 'Anonymous'
    image.onload = () => {
      try {
        const canvas = document.createElement('canvas')
        canvas.width = image.width
        canvas.height = image.height
        const ctx = canvas.getContext('2d')
        ctx.drawImage(image, 0, 0)
        resolve(canvas.toDataURL('image/png'))
      } catch (error) {
        reject(error)
      }
    }
    image.onerror = reject
    image.src = src
  })

  const logoSrc = '../images/img_logotipo.png'
  const logoDataUrl = await loadImageAsDataUrl(logoSrc).catch(() => null)

  const headerHeight = 88
  doc.setFillColor(25, 135, 84)
  doc.rect(0, 0, pageWidth, headerHeight, 'F')
  if (logoDataUrl) {
    doc.addImage(logoDataUrl, 'PNG', margin, 18, 48, 48)
  }
  doc.setTextColor(255, 255, 255)
  doc.setFontSize(22)
  doc.text('BioUrbis', pageWidth / 2, 34, { align: 'center' })
  doc.setFontSize(12)
  doc.text('Reporte de Jardineras', pageWidth / 2, 52, { align: 'center' })
  doc.setFontSize(9)
  doc.text(`Fecha: ${new Date().toLocaleDateString('es-CO')}`, margin, 72)
  doc.text(`Total jardineras: ${selectedGardens.length}`, pageWidth - margin, 72, { align: 'right' })
  doc.setLineWidth(1)
  doc.setDrawColor(255, 255, 255)
  doc.line(margin, headerHeight - 4, pageWidth - margin, headerHeight - 4)

  cursorY = headerHeight + 18

  const reportType = elements.reportType?.value || 'monthly'
  const reportTypeLabel = reportType === 'weekly' ? 'Semanal' : reportType === 'custom' ? 'Personalizado' : 'Mensual'
  const selectedGardenLabel = elements.reportGarden?.selectedOptions?.[0]?.text || 'Todas las semillas'
  const alertasGlobal = Array.isArray(window.alertas) ? window.alertas : []
  const activeAlertCountGlobal = alertasGlobal.length

  addPageIfNeeded(90)
  doc.setFillColor(232, 250, 244)
  doc.setDrawColor(179, 221, 181)
  doc.roundedRect(margin, cursorY, contentWidth, 80, 10, 10, 'FD')
  doc.setTextColor(13, 62, 50)
  doc.setFontSize(13)
  doc.text('Resumen del reporte', pageWidth / 2, cursorY + 24, { align: 'center' })
  doc.setFontSize(10)
  doc.text(`Tipo de reporte: ${reportTypeLabel}`, margin + 16, cursorY + 44)
  doc.text(`Semilla: ${selectedGardenLabel}`, pageWidth - margin - 16, cursorY + 44, { align: 'right' })
  doc.setFontSize(9)
  doc.text(`Jardineras: ${selectedGardens.length}`, margin + 16, cursorY + 62)
  doc.text(`Alertas activas: ${activeAlertCountGlobal} (${activeAlertCountGlobal > 0 ? 'Sí' : 'No'})`, pageWidth - margin - 16, cursorY + 62, { align: 'right' })
  cursorY += 102

  const userInfo = window.userReportData || {}
  addPageIfNeeded(110)
  doc.setFillColor(255, 255, 255)
  doc.setDrawColor(179, 221, 181)
  doc.roundedRect(margin, cursorY, contentWidth, 100, 10, 10, 'FD')
  doc.setTextColor(16, 63, 40)
  doc.setFontSize(13)
  doc.text('Datos del usuario', pageWidth / 2, cursorY + 26, { align: 'center' })
  doc.setFontSize(10)
  doc.text(`Nombre: ${userInfo.nombre || 'No disponible'}`, margin + 14, cursorY + 46)
  doc.text(`Documento: ${userInfo.documento || 'No disponible'}`, margin + 14, cursorY + 62)
  doc.text(`Correo: ${userInfo.correo || 'No disponible'}`, margin + contentWidth / 2, cursorY + 46)
  doc.text(`Barrio: ${userInfo.barrio || 'No disponible'}`, margin + contentWidth / 2, cursorY + 62)

  cursorY += 122

  selectedGardens.forEach((garden, index) => {
    const factorCount = Array.isArray(garden.factoresExternos) ? garden.factoresExternos.length : 0
    const evolutionCount = Array.isArray(garden.evoluciones) ? garden.evoluciones.length : 0
    const gardenAlertCount = alertasGlobal.filter(a => a.idJardinera === garden.idJardinera).length
    const gardenAlertText = gardenAlertCount > 0 ? 'Sí' : 'No'

    const gardenDetails = [
      `Descripción: ${garden.jarDescripcion || 'No disponible'}`,
      `Semilla: ${garden.semNombre || 'N/A'}`,
      `Fase actual: ${garden.faseNombre || 'N/A'}`,
      `Progreso: ${garden.jarPorcentajeEvolucion || '0'}%`,
      `Creación: ${garden.jarFechaCreacion || 'N/A'}`
    ]

    const statsLines = [
      `Factores externos: ${factorCount}`,
      `Evoluciones: ${evolutionCount}`,
      `Alerta activa: ${gardenAlertText}`
    ]

    const factorLines = factorCount > 0
      ? garden.factoresExternos.map((factor, factorIndex) => `• Factor ${factorIndex + 1}: Humedad ${factor.humedad || 'N/A'}, Agua ${factor.cantidadAgua || 'N/A'}, Temperatura ${factor.temperatura || 'N/A'}, Clima ${factor.clima || 'N/A'}`)
      : ['• No hay factores externos registrados.']

    const evolutionLines = evolutionCount > 0
      ? garden.evoluciones.map((evolucion, evoIndex) => `• Evolución ${evoIndex + 1}: ${evolucion.fecha || 'Fecha no registrada'} — ${evolucion.nota || 'Sin nota'} (${evolucion.porcentaje || '0'}%)`)
      : ['• No hay evoluciones registradas.']

    const detailTextLines = gardenDetails.flatMap(line => doc.splitTextToSize(line, contentWidth - 28))
    const statsTextLines = statsLines.flatMap(line => doc.splitTextToSize(line, contentWidth - 28))
    const factorTextLines = factorLines.flatMap(line => doc.splitTextToSize(line, contentWidth - 46))
    const evolutionTextLines = evolutionLines.flatMap(line => doc.splitTextToSize(line, contentWidth - 46))

    const gardenCardHeight = 30 + detailTextLines.length * 14 + statsTextLines.length * 14 + 18 + factorTextLines.length * 14 + 18 + evolutionTextLines.length * 14 + 22
    const totalGardenHeight = 28 + gardenCardHeight + 20

    addPageIfNeeded(totalGardenHeight)
    doc.setFontSize(14)
    doc.setTextColor(18, 81, 50)
    doc.text(`Jardinera ${index + 1}: ${garden.jarNombre || 'Sin nombre'}`, margin, cursorY)
    cursorY += 24

    doc.setFillColor(255, 255, 255)
    doc.setDrawColor(187, 227, 188)
    doc.roundedRect(margin, cursorY, contentWidth, gardenCardHeight, 10, 10, 'FD')

    let innerY = cursorY + 24
    doc.setFontSize(11)
    doc.setTextColor(15, 74, 41)
    doc.text(detailTextLines, margin + 14, innerY)
    innerY += detailTextLines.length * 14 + 8

    doc.setFontSize(10)
    doc.setTextColor(25, 99, 55)
    doc.text(statsTextLines, margin + 14, innerY)
    innerY += statsTextLines.length * 14 + 12

    doc.setFontSize(11)
    doc.setTextColor(13, 89, 48)
    doc.text('Factores externos', margin + 14, innerY)
    innerY += 16
    doc.setFontSize(10)
    doc.setTextColor(39, 61, 41)
    factorTextLines.forEach((line) => {
      doc.text(line, margin + 22, innerY)
      innerY += 14
    })
    innerY += 10

    doc.setFontSize(11)
    doc.setTextColor(13, 89, 48)
    doc.text('Evolución', margin + 14, innerY)
    innerY += 16
    doc.setFontSize(10)
    doc.setTextColor(39, 61, 41)
    evolutionTextLines.forEach((line) => {
      doc.text(line, margin + 22, innerY)
      innerY += 14
    })

    cursorY += gardenCardHeight + 20
  })

  doc.setTextColor(120, 120, 120)
  doc.setFontSize(9)
  doc.text('Generado por BioUrbis', margin, pageHeight - 28)

  const fecha = new Date().toLocaleDateString('es-CO').replace(/\//g, '-')

  const tipoReporte = reportTypeLabel.replace(/\s/g, '_')

  const semilla = selectedGardenLabel.replace(/\s/g, '_')

  const nombreArchivo = `Reporte_${tipoReporte}_${semilla}_${fecha}.pdf`

  doc.save(nombreArchivo)
  mostrarMensaje({
    title:"¡El reporte de jardineras ha sido generado y descargado en su dispositivo!",
    text:"Revise su carpeta de descargas para encontrar el archivo PDF con toda la información detallada sobre sus jardineras",
    icon:"success",
                                                            
    //Si el usuario acepta revisar su carpeta de descargas
    rutaTrue:"homeUsuario.php?page=reports",

    //Si el usuario no acepta revisar su carpeta de descargas
    rutaFalse:"homeUsuario.php?page=reports"
  })
}

function deleteReport(id) {
  mostrarMensaje({
    title:"¡Reporte eliminado exitosamente!",
    text:"El reporte ha sido eliminado de su dispositivo",
    icon:"success",
                                                            
    //Si el usuario acepta revisar su carpeta de descargas
    rutaTrue:"homeUsuario.php?page=reports",

    //Si el usuario no acepta revisar su carpeta de descargas
    rutaFalse:"homeUsuario.php?page=reports"
  })
}

// ================== EXPOSICIÓN GLOBAL ==================
Object.assign(window, {
  deleteReport
})

function cerrarSesion() {
  mostrarMensaje({
    title:"¿Desea cerrar sesión?",
    text:"Si cierra sesión, deberá volver a ingresar sus credenciales para acceder a su cuenta",
    icon:"error",
                                                            
    //Si el usuario acepta cerrar sesión, redirigir a la página de inicio de sesión
    rutaTrue:"../php/cerrarSesion.php",

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
