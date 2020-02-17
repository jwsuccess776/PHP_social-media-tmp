<?php
/**
 * Класс позволяет осуществлять параллельный доступ к разделяемым ресурсам.
 *
 * При вызове {@link lock()} блокирует вызывающий поток, если ресурс занят, и разблокирует его {@link release()}
 * только когда ресурс освободится (или если блокирующий поток завершился).
 *
 * Использует файл (lock-файл) для блокировки остальных конкурентов на время выполнения
 * операции. Хранит этот файл в папке /tmp. Можно указать другую папку в конструкторе.
 *
 * @package core
 */
class PaLock {

    /**
     * Путь к папке, где хранятся файлы блокировки.
     *
     * @var string
     * @access private
     */
    var $tmpPath;

    /**
     * Имя объекта блокировки.
     *
     * @var string
     * @access private
     */
    var $name;

    /**
     * Полный путь к файлу для блокировки.
     *
     * @var string
     * @access private
     */
    var $lockFile;

    /**
     * Проинициализирован ли объект.
     *
     * @var boolean
     * @access private
     */
    var $isInitialized = false;

    /**
     * Handler of the locked file.
     *
     * @var resource
     * @access private
     */
    var $hFile = null;

    /**
     * Делает правильным путь в хранилище:
     * <ul>
     * <li>убирает дублирование слешей;</li>
     * <li>добавляет финальный слеш (если его нет).</li>
     * </ul>
     *
     * @param string $path
     * @param boolean $finalSlash Должен или нет быть финальный слеш в конце пути
     * @return string
     * @access public
     */
    function normalizeStoragePath($path, $finalSlash = true)
    {
        $path = preg_replace("`/+`", "/", $path);
        $path = preg_replace("`/$`", "", $path);
        if ($finalSlash && strlen($path) > 0 && $path[strlen($path)-1] != "/") {
            $path .= "/";
        }

        return $path;
    }

    /**
     * Создать объект блокировки с именем $name.
     *
     * @param string $name
     * @param string $tmpPath Папка, где хранятся файлы блокировки.
     */
    function PaLock($name, $tmpPath = "/tmp")
    {
        $this->name = $name;
        $this->tmpPath = $this->normalizeStoragePath($tmpPath, false);
    }

    /**
     * Инициализировать объект.
     *
     * Генерирует PaError в случае ошибки доступа к /tmp.
     *
     * @access private
     */
    function init()
    {
        if (!$this->isInitialized) {
            if (!is_writable($this->tmpPath)) {
                die("Can't write to $this->tmpPath.". __FILE__. __LINE__);
            }

            $this->lockFile = $this->tmpPath."/#lock".$this->name;

            $this->isInitialized = true;
        }
    }

    /**
     * Пытается получить доступ к разделяемому объекту.
     *
     * Если объект занят то не выходит, пока объект не освободится либо не произойдёт
     * ошибки.
     *
     * @param boolean $notBlocking Должна ли блокировка блокировать поток в случае не
     * возможности её моментального получения.
     * @return boolean Прошла ли блокировка успешно.
     * @access public
     */
    function lock($notBlocking = false)
    {
        $this->init();

        $this->hFile = @fopen($this->lockFile, "a+");
        if ($this->hFile === false) {
            return false;
        }
        $res = @flock($this->hFile, LOCK_EX | ($notBlocking ? 0 : LOCK_NB));
        if ($res === false) {
            @fclose($this->hFile);
            $this->hFile = null;
            return false;
        }

        return true;
    }

    /**
     * Освободить заблокированный объект.
     *
     * @return boolean Прошла ли разблокировка успешно.
     * @access public
     */
    function release()
    {
        $this->init();

        if ($this->hFile === null) {
            return false;
        }

        $this->hFile = @fopen($this->lockFile, "a+");
        if ($this->hFile === false) {
            return false;
        }
        $res = @flock($this->hFile, LOCK_UN);
        if ($res === false) {
            @fclose($this->hFile);
            $this->hFile = null;
            return false;
        }

        @fclose($this->hFile);
        $this->hFile = null;

        return true;
    }
}
?>