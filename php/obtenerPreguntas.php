<?php
include("../functions/funciones.php");

$idFase = $_GET['idFase'];

$resultadoConsultarPreguntas = consultarPreguntasPorFase($idFase);

while($datosPregunta = mysqli_fetch_assoc($resultadoConsultarPreguntas)){
    $idPregunta=$datosPregunta["idPregunta"];
    ?>
    <div class="vf-item">
        <span><?php echo $datosPregunta["pregDescripcion"]; ?></span>
        <div class="vf-options">
            <label>
                <input type="radio" name="preg_<?php echo $idPregunta; ?>" value="<?php echo $datosPregunta['pregPorcentaje'] ?>" required>
                <span class="circle yes"></span>
            </label>
            <label>
                <input type="radio" name="preg_<?php echo $idPregunta; ?>" value="0">
                <span class="circle no"></span>
            </label>
        </div>
    </div>
    <?php
}
?>
