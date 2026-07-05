<?php

include("../functions/funciones.php");

$idFase = $_GET['idFase'];

$resultadoConsultarPreguntas = consultarPreguntasPorFase($idFase);
if(mysqli_num_rows($resultadoConsultarPreguntas)>0){ ?>
    <table>
        <tr>   
            <th>Identificador</th>
            <th>Pregunta</th>
            <th>Porcentaje</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
        <?php
        while($datosPregunta = mysqli_fetch_assoc($resultadoConsultarPreguntas)){
            ?>
            <tr>
                <td><?php echo $datosPregunta["idPregunta"]; ?></td>
                <td><?php echo $datosPregunta["pregDescripcion"]; ?></td>
                <td><?php echo $datosPregunta["pregPorcentaje"]; ?>%</td>
                <td><?php echo $datosPregunta["pregEstado"]; ?></td>
                <td class="acciones">
                    <button class="table-button updateStageQuestionsBtn" id="updateStageQuestionsBtn"
                            data-id="<?php echo $datosPregunta["idPregunta"]; ?>">
                        <i class="fas fa-edit"></i>
                    </button>

                    <button class="table-button inactivateStageQuestionsBtn" id="inactivateStageQuestionsBtn"
                            data-id="<?php echo $datosPregunta["idPregunta"]; ?>">
                        <i class="fas fa-ban"></i>
                    </button>
                </td>
            </tr>
            <?php
        } 
        ?>
    </table>
    <?php
}else{
    ?>
    <div class="empty-state full-width">
        <div class="empty-state-icon">
            <i class="fas fa-folder-open"></i>
        </div>
        <h3>No hay preguntas disponibles</h3>
        <p>No existen preguntas registradas para la fase seleccionada en el sistema. Puede registrar una nueva para comenzar.</p>
    </div>
<?php }
?>