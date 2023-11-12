<div class="col-lg-2">
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="hori-timeline px-1" dir="ltr">
                <h3 class="mb-3">{{ __('site.history') }}</h3>
                <div class="timeline">
                    <ul class="timeline events">
                        <li class="timeline-item mb-5">
                            <h5 class="fw-bold fs-18">Включване на проекта</h5>
                            <p class="mb-2 fw-bold">12.05.2023</p>
                            <p> Tова събитие описва запис на акт в ЗП или ОП.</p>
                        </li>
                        <li class="timeline-item mb-5">
                            <h5 class="fw-bold fs-18">Начало на обществената консултация</h5>
                            <p class="mb-2 fw-bold">{{ displayDate($item->open_from) }}</p>
                            <p> Визуализира се „Начало на консултацията“.</p>
                        </li>
                        <li class="timeline-item mb-5">
                            <h5 class="fw-bold fs-18">Промяна на файл </h5>
                            <p class="mb-2 fw-bold">25.05.2023</p>
                            <p> Промяна на файл от консултацията.</p>
                        </li>
                        <li class="timeline-item mb-5">
                            <h5 class="fw-bold text-muted fs-18">Приключване на консултацията</h5>
                            <p class="text-muted mb-2 fw-bold ">{{ displayDate($item->open_to) }}</p>
                            <p class="text-muted">Край на консултацията</p>
                        </li>
                        <li class="timeline-item mb-5">
                            <h5 class="fw-bold text-muted fs-18">Справка за получените предложения</h5>
                            <p class="text-muted mb-2 fw-bold">15.06.2023</p>
                            <p class="text-muted">Справка или съобщение.</p>
                        </li>
                        <li class="timeline-item mb-5">
                            <h5 class="fw-bold text-muted fs-18">Приемане на акта от Министерския съвет</h5>
                            <p class="text-muted mb-2 fw-bold text-muted">18.06.2023</p>
                            <p class="text-muted">Окончателен акт.</p>
                        </li>
                        <li class="timeline-item mb-5">
                            <h5 class="fw-bold text-muted fs-18"> Представяне на законопроекта</h5>
                            <p class="text-muted mb-2 fw-bold ">25.06.2023</p>
                            <p class="text-muted">Развито в обхвата на текущата поръчка.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
