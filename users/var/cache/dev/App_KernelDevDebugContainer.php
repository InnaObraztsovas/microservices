<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerR81BTI5\App_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerR81BTI5/App_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerR81BTI5.legacy');

    return;
}

if (!\class_exists(App_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerR81BTI5\App_KernelDevDebugContainer::class, App_KernelDevDebugContainer::class, false);
}

return new \ContainerR81BTI5\App_KernelDevDebugContainer([
    'container.build_hash' => 'R81BTI5',
    'container.build_id' => '114bdee5',
    'container.build_time' => 1666011065,
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerR81BTI5');
